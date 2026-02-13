<?php
declare(strict_types=1);
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Booking Saya';

// Handle cancel action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) {
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare('UPDATE bookings SET status = "cancelled" WHERE id = ? AND status = "pending"');
        $stmt->execute([(int) $_POST['cancel_booking_id']]);
        session_start();
        $_SESSION['flash_success'] = 'Booking berhasil dibatalkan.';
        header('Location: my-bookings.php');
        exit;
    } catch (Exception $e) {
        // Silently fail
    }
}

// Fetch all bookings
try {
    $pdo  = getConnection();
    $stmt = $pdo->query('
        SELECT b.*, s.name AS service_name, s.duration_minutes, t.label AS time_label
        FROM bookings b
        JOIN services s ON b.service_id = s.id
        JOIN time_slots t ON b.time_slot_id = t.id
        ORDER BY b.created_at DESC
    ');
    $bookings = $stmt->fetchAll();
} catch (Exception $e) {
    $bookings = [];
}

// Flash messages
session_start();
$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError   = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

include __DIR__ . '/includes/header.php';
?>

    <?php if ($flashSuccess): ?>
        <div id="flashSuccess" data-message="<?= htmlspecialchars($flashSuccess) ?>"></div>
    <?php endif; ?>
    <?php if ($flashError): ?>
        <div id="flashError" data-message="<?= htmlspecialchars($flashError) ?>"></div>
    <?php endif; ?>

    <section class="bookings-section">
        <div class="container">
            <div class="section-header fade-in">
                <div class="section-label"><i class="bi bi-list-check"></i> Daftar Booking</div>
                <h2 class="section-title">Booking Saya</h2>
                <p class="section-desc">Berikut daftar semua reservasi yang telah dibuat.</p>
            </div>

            <!-- Search -->
            <div class="search-bar fade-in">
                <i class="bi bi-search"></i>
                <input type="text" class="form-input" placeholder="Cari berdasarkan kode, nama, atau layanan..." id="searchBooking">
            </div>

            <?php if (empty($bookings)): ?>
                <div class="empty-state fade-in">
                    <i class="bi bi-calendar-x"></i>
                    <h3>Belum Ada Booking</h3>
                    <p>Anda belum memiliki booking. Mulai buat booking pertama Anda!</p>
                    <a href="booking.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i>
                        <span>Buat Booking</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="bookings-grid">
                    <?php foreach ($bookings as $i => $bk): ?>
                        <div class="booking-card fade-in" style="transition-delay: <?= min($i * 0.08, 0.5) ?>s">
                            <div class="booking-card-header">
                                <span class="booking-code"><?= htmlspecialchars($bk['booking_code']) ?></span>
                                <span class="booking-status status-<?= $bk['status'] ?>">
                                    <?= ucfirst($bk['status']) ?>
                                </span>
                            </div>
                            <div class="booking-info">
                                <div class="booking-info-item">
                                    <i class="bi bi-person"></i>
                                    <span><?= htmlspecialchars($bk['customer_name']) ?></span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="bi bi-briefcase"></i>
                                    <span><?= htmlspecialchars($bk['service_name']) ?></span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="bi bi-calendar3"></i>
                                    <span><?= date('d M Y', strtotime($bk['booking_date'])) ?></span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="bi bi-clock"></i>
                                    <span><?= htmlspecialchars($bk['time_label']) ?> (<?= $bk['duration_minutes'] ?> menit)</span>
                                </div>
                                <?php if (!empty($bk['notes'])): ?>
                                    <div class="booking-info-item">
                                        <i class="bi bi-chat-text"></i>
                                        <span><?= htmlspecialchars($bk['notes']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="booking-card-footer">
                                <span class="booking-price"><?= formatRupiah((float)$bk['total_price']) ?></span>
                                <?php if ($bk['status'] === 'pending'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="cancel_booking_id" value="<?= $bk['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm btn-cancel-booking">
                                            <i class="bi bi-x-circle"></i> Batalkan
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
