<?php
declare(strict_types=1);
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Booking Berhasil';

$bookingCode = $_GET['code'] ?? '';

$booking = null;
if ($bookingCode !== '') {
    try {
        $pdo  = getConnection();
        $stmt = $pdo->prepare('
            SELECT b.*, s.name AS service_name, s.duration_minutes, t.label AS time_label
            FROM bookings b
            JOIN services s ON b.service_id = s.id
            JOIN time_slots t ON b.time_slot_id = t.id
            WHERE b.booking_code = ?
        ');
        $stmt->execute([$bookingCode]);
        $booking = $stmt->fetch();
    } catch (Exception $e) {
        $booking = null;
    }
}

include __DIR__ . '/includes/header.php';
?>

    <section class="success-container">
        <div class="container">
            <?php if ($booking): ?>
                <div class="fade-in">
                    <div class="success-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <h2>Booking Berhasil! 🎉</h2>
                    <p>Terima kasih, <strong><?= htmlspecialchars($booking['customer_name']) ?></strong>. Booking Anda telah berhasil dibuat.</p>
                    <p style="color: var(--slate-500); font-size: 0.9rem;">Simpan kode booking Anda:</p>

                    <div class="success-code"><?= htmlspecialchars($booking['booking_code']) ?></div>

                    <div style="max-width: 400px; margin: 0 auto; text-align: left;">
                        <div class="booking-info">
                            <div class="booking-info-item">
                                <i class="bi bi-briefcase"></i>
                                <span><?= htmlspecialchars($booking['service_name']) ?></span>
                            </div>
                            <div class="booking-info-item">
                                <i class="bi bi-calendar3"></i>
                                <span><?= date('d F Y', strtotime($booking['booking_date'])) ?></span>
                            </div>
                            <div class="booking-info-item">
                                <i class="bi bi-clock"></i>
                                <span><?= htmlspecialchars($booking['time_label']) ?> (<?= $booking['duration_minutes'] ?> menit)</span>
                            </div>
                            <div class="booking-info-item">
                                <i class="bi bi-cash-coin"></i>
                                <span style="color: var(--accent-400); font-weight: 700;"><?= formatRupiah((float)$booking['total_price']) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="success-actions">
                        <a href="my-bookings.php" class="btn btn-primary">
                            <i class="bi bi-list-check"></i>
                            <span>Lihat Booking Saya</span>
                        </a>
                        <a href="index.php" class="btn btn-outline">
                            <i class="bi bi-house"></i>
                            <span>Kembali ke Beranda</span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state fade-in">
                    <i class="bi bi-question-circle"></i>
                    <h3>Booking Tidak Ditemukan</h3>
                    <p>Kode booking tidak valid atau tidak ditemukan di sistem.</p>
                    <a href="booking.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i>
                        <span>Buat Booking Baru</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
