<?php
declare(strict_types=1);
require_once __DIR__ . '/config/database.php';

/**
 * Process Booking Form Submission
 */

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: booking.php');
    exit;
}

// Sanitize input
$customerName  = trim($_POST['customer_name'] ?? '');
$customerEmail = trim($_POST['customer_email'] ?? '');
$customerPhone = trim($_POST['customer_phone'] ?? '');
$serviceId     = (int) ($_POST['service_id'] ?? 0);
$bookingDate   = trim($_POST['booking_date'] ?? '');
$timeSlotId    = (int) ($_POST['time_slot_id'] ?? 0);
$notes         = trim($_POST['notes'] ?? '');

// Validate
$errors = [];

if ($customerName === '') {
    $errors[] = 'Nama wajib diisi.';
}

if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Format email tidak valid.';
}

if (!preg_match('/^[\d+\-\s()]{8,20}$/', $customerPhone)) {
    $errors[] = 'Nomor telepon tidak valid.';
}

if ($serviceId <= 0) {
    $errors[] = 'Pilih layanan.';
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $bookingDate)) {
    $errors[] = 'Format tanggal tidak valid.';
} else {
    $dateObj = new DateTime($bookingDate);
    $today   = new DateTime('today');
    if ($dateObj < $today) {
        $errors[] = 'Tanggal booking tidak boleh di masa lalu.';
    }
}

if ($timeSlotId <= 0) {
    $errors[] = 'Pilih waktu.';
}

// If validation fails, redirect back
if (!empty($errors)) {
    session_start();
    $_SESSION['flash_error'] = implode(' ', $errors);
    header('Location: booking.php');
    exit;
}

try {
    $pdo = getConnection();

    // Get service price
    $stmt = $pdo->prepare('SELECT price FROM services WHERE id = ? AND is_active = 1');
    $stmt->execute([$serviceId]);
    $service = $stmt->fetch();

    if (!$service) {
        throw new Exception('Layanan tidak ditemukan.');
    }

    $totalPrice  = (float) $service['price'];
    $bookingCode = generateBookingCode();

    // Check for duplicate booking code (very unlikely but safe)
    $checkStmt = $pdo->prepare('SELECT id FROM bookings WHERE booking_code = ?');
    $checkStmt->execute([$bookingCode]);
    if ($checkStmt->fetch()) {
        $bookingCode = generateBookingCode(); // Regenerate
    }

    // Insert booking
    $insertStmt = $pdo->prepare('
        INSERT INTO bookings (booking_code, customer_name, customer_email, customer_phone, service_id, booking_date, time_slot_id, notes, status, total_price)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, "pending", ?)
    ');

    $insertStmt->execute([
        $bookingCode,
        $customerName,
        $customerEmail,
        $customerPhone,
        $serviceId,
        $bookingDate,
        $timeSlotId,
        $notes,
        $totalPrice
    ]);

    // Redirect to success page
    header('Location: booking_success.php?code=' . urlencode($bookingCode));
    exit;

} catch (Exception $e) {
    session_start();
    $_SESSION['flash_error'] = 'Terjadi kesalahan: ' . $e->getMessage();
    header('Location: booking.php');
    exit;
}
