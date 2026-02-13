<?php
declare(strict_types=1);
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Beranda';

// Fetch services for the landing page
try {
    $pdo = getConnection();
    $stmt = $pdo->query('SELECT * FROM services WHERE is_active = 1 ORDER BY price ASC');
    $services = $stmt->fetchAll();
} catch (Exception $e) {
    $services = [];
}

include __DIR__ . '/includes/header.php';
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-badge">
                <i class="bi bi-lightning-charge-fill"></i>
                Platform Booking #1 Indonesia
            </div>
            <h1>
                Reservasi Layanan<br>
                <span class="gradient-text">Lebih Mudah & Cepat</span>
            </h1>
            <p>Nikmati kemudahan booking layanan terbaik secara online. Pilih layanan, tentukan waktu, dan konfirmasi — semua dalam hitungan menit.</p>
            <div class="hero-actions">
                <a href="booking.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-calendar-plus"></i>
                    <span>Buat Booking Sekarang</span>
                </a>
                <a href="#layanan" class="btn btn-outline btn-lg">
                    <i class="bi bi-arrow-down-circle"></i>
                    <span>Lihat Layanan</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card fade-in">
                    <div class="stat-number">5,200+</div>
                    <div class="stat-label">Booking Selesai</div>
                </div>
                <div class="stat-card fade-in">
                    <div class="stat-number">4.9 ⭐</div>
                    <div class="stat-label">Rating Rata-rata</div>
                </div>
                <div class="stat-card fade-in">
                    <div class="stat-number">150+</div>
                    <div class="stat-label">Klien Setia</div>
                </div>
                <div class="stat-card fade-in">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Layanan Online</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="section" id="layanan">
        <div class="container">
            <div class="section-header fade-in">
                <div class="section-label"><i class="bi bi-grid"></i> Layanan Kami</div>
                <h2 class="section-title">Pilih Layanan Terbaik untuk Anda</h2>
                <p class="section-desc">Beragam pilihan layanan profesional dengan kualitas terjamin dan harga transparan.</p>
            </div>

            <div class="services-grid">
                <?php if (empty($services)): ?>
                    <div class="empty-state" style="grid-column:1/-1;">
                        <i class="bi bi-exclamation-circle"></i>
                        <h3>Tidak ada layanan tersedia</h3>
                        <p>Pastikan database sudah disetup dengan benar.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($services as $i => $service): ?>
                        <div class="service-card fade-in" style="transition-delay: <?= $i * 0.1 ?>s">
                            <div class="service-icon">
                                <i class="bi <?= htmlspecialchars($service['icon']) ?>"></i>
                            </div>
                            <h3 class="service-name"><?= htmlspecialchars($service['name']) ?></h3>
                            <p class="service-desc"><?= htmlspecialchars($service['description']) ?></p>
                            <div class="service-meta">
                                <span class="service-price"><?= formatRupiah((float)$service['price']) ?></span>
                                <span class="service-duration">
                                    <i class="bi bi-clock"></i>
                                    <?= (int)$service['duration_minutes'] ?> menit
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section class="section">
        <div class="container">
            <div class="section-header fade-in">
                <div class="section-label"><i class="bi bi-signpost-2"></i> Cara Kerja</div>
                <h2 class="section-title">Booking dalam 3 Langkah Mudah</h2>
                <p class="section-desc">Proses cepat dan sederhana untuk mendapatkan layanan yang Anda butuhkan.</p>
            </div>

            <div class="steps-grid">
                <div class="step-card fade-in">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Pilih Layanan</h3>
                    <p class="step-desc">Jelajahi berbagai pilihan layanan kami dan pilih yang sesuai dengan kebutuhan Anda.</p>
                </div>
                <div class="step-card fade-in" style="transition-delay: 0.15s">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Tentukan Jadwal</h3>
                    <p class="step-desc">Pilih tanggal dan waktu yang Anda inginkan dari slot yang tersedia.</p>
                </div>
                <div class="step-card fade-in" style="transition-delay: 0.3s">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Konfirmasi Booking</h3>
                    <p class="step-desc">Isi data diri singkat, konfirmasi, dan Anda akan mendapat kode booking unik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section">
        <div class="container" style="text-align:center;">
            <div class="fade-in">
                <h2 class="section-title">Siap Memulai?</h2>
                <p class="section-desc" style="margin-bottom: 32px;">Buat booking pertama Anda sekarang dan rasakan pengalaman layanan terbaik.</p>
                <a href="booking.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-rocket-takeoff"></i>
                    <span>Mulai Booking</span>
                </a>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
