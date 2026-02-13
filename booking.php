<?php
declare(strict_types=1);
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Buat Booking';

// Fetch active services and time slots
try {
    $pdo = getConnection();
    $services  = $pdo->query('SELECT id, name, price, duration_minutes FROM services WHERE is_active = 1 ORDER BY price ASC')->fetchAll();
    $timeSlots = $pdo->query('SELECT id, label FROM time_slots WHERE is_active = 1 ORDER BY slot_time ASC')->fetchAll();
} catch (Exception $e) {
    $services = [];
    $timeSlots = [];
}

include __DIR__ . '/includes/header.php';
?>

    <section class="booking-section">
        <div class="container booking-container">
            <div class="form-card fade-in">
                <div class="form-header">
                    <h2>Buat Reservasi Baru</h2>
                    <p>Lengkapi formulir di bawah untuk memesan layanan Anda</p>
                </div>

                <form id="bookingForm" action="process_booking.php" method="POST" novalidate>
                    <div class="form-grid">
                        <!-- Nama -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-person"></i>
                                Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" name="customer_name" class="form-input" placeholder="Masukkan nama lengkap" required id="fieldName">
                            <span class="field-error">Nama wajib diisi</span>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-envelope"></i>
                                Email <span class="required">*</span>
                            </label>
                            <input type="email" name="customer_email" class="form-input" placeholder="contoh@email.com" required id="fieldEmail">
                            <span class="field-error">Format email tidak valid</span>
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-phone"></i>
                                No. Telepon <span class="required">*</span>
                            </label>
                            <input type="tel" name="customer_phone" class="form-input" placeholder="08xx-xxxx-xxxx" required id="fieldPhone">
                            <span class="field-error">No. telepon tidak valid</span>
                        </div>

                        <!-- Service -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-briefcase"></i>
                                Pilih Layanan <span class="required">*</span>
                            </label>
                            <select name="service_id" class="form-select" required id="fieldService">
                                <option value="">-- Pilih Layanan --</option>
                                <?php foreach ($services as $svc): ?>
                                    <option value="<?= $svc['id'] ?>">
                                        <?= htmlspecialchars($svc['name']) ?> — <?= formatRupiah((float)$svc['price']) ?> (<?= $svc['duration_minutes'] ?> menit)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-error">Pilih layanan</span>
                        </div>

                        <!-- Date -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-calendar3"></i>
                                Tanggal Booking <span class="required">*</span>
                            </label>
                            <input type="date" name="booking_date" class="form-input" required id="fieldDate">
                            <span class="field-error">Tanggal tidak valid (harus hari ini atau setelahnya)</span>
                        </div>

                        <!-- Time Slot -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-clock"></i>
                                Waktu <span class="required">*</span>
                            </label>
                            <select name="time_slot_id" class="form-select" required id="fieldTime">
                                <option value="">-- Pilih Waktu --</option>
                                <?php foreach ($timeSlots as $slot): ?>
                                    <option value="<?= $slot['id'] ?>"><?= htmlspecialchars($slot['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-error">Pilih waktu</span>
                        </div>

                        <!-- Notes -->
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="bi bi-chat-text"></i>
                                Catatan (opsional)
                            </label>
                            <textarea name="notes" class="form-textarea" placeholder="Tambahan informasi atau permintaan khusus..." id="fieldNotes"></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit">
                            <div class="spinner"></div>
                            <i class="bi bi-check2-circle"></i>
                            <span>Konfirmasi Booking</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
