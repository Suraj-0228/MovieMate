<?php
session_start();
require 'controllers/booking-details.php';
require 'includes/dbconnection.php';

// ===== Update booking status if admin takes action =====
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $status = ($_GET['action'] == 'approve') ? 'Approved' : 'Cancelled';
    mysqli_query($con, "UPDATE bookings SET booking_status='$status' WHERE booking_id='$id'");
    header("Location: manage_bookings.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMate - Manage Bookings</title>

    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="../assets/img/favicon.svg" type="image/x-icon">
</head>

<body>

    <main class="d-flex">

        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Manage Booking Section -->
        <section class="content-area flex-grow-1" style="margin-left: 260px; padding: 20px;">
            <section class="container my-3 px-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 text-primary">
                    <h1 class="fw-bold text-uppercase mb-3 mb-md-0">
                        <i class="fa-solid fa-ticket me-2"></i> Manage Bookings
                    </h1>
                </div>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <div class="row g-4">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $booking_id      = $row['booking_id'];
                            $username        = htmlspecialchars($row['username']);
                            $movie_title     = htmlspecialchars($row['title']);
                            $show_date       = $row['show_date'];
                            $show_time       = date("h:i A", strtotime($row['show_time']));
                            $theater_name    = htmlspecialchars($row['theater_name']);
                            $seat_row        = $row['seat_row'] ?? '';
                            $total_seat      = $row['total_seat'] ?? '';
                            $amount          = number_format($row['amount'], 2);
                            $booking_status  = $row['booking_status'] ?? 'Pending';
                            $payment_method  = $row['payment_method'] ?? 'N/A';
                            $payment_status  = $row['payment_status'] ?? 'Pending';
                            $status_badge = match ($booking_status) {
                                'Pending'   => 'bg-warning text-dark',
                                'Approved'  => 'bg-success',
                                'Cancelled' => 'bg-danger',
                                default     => 'bg-secondary'
                            };
                            $method_badge = match ($payment_method) {
                                'UPI'  => 'bg-success',
                                'Card' => 'bg-info text-dark',
                                'Cash' => 'bg-secondary',
                                default => 'bg-light text-dark'
                            };
                            $payment_badge = match ($payment_status) {
                                'Confirmed' => 'bg-success',
                                'Pending'   => 'bg-warning text-dark',
                                'Failed'    => 'bg-danger',
                                default     => 'bg-secondary'
                            };
                            ?>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                    <div class="card-header admin-header text-white d-flex justify-content-between align-items-center">
                                        <span class="">
                                            Booking ID: #<?= $booking_id ?>
                                        </span>
                                        <span class="badge <?= $status_badge ?> px-3 py-2 fw-semibold">
                                            <?= $booking_status ?>
                                        </span>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-bold mb-1 text-truncate" title="<?= $movie_title ?>">
                                            <i class="fa-solid fa-film me-2 text-primary"></i><?= $movie_title ?>
                                        </h5>
                                        <p class="mb-2 text-muted">
                                            <i class="fa-solid fa-user me-1"></i><?= $username ?>
                                        </p>
                                        <p class="mb-2 d-flex flex-row align-items-center">
                                            <i class="fa-solid fa-calendar-days me-1 text-primary"></i><?= $show_date ?>
                                            <i class="fa-solid ms-3 fa-clock me-1 text-danger"></i><?= $show_time ?>
                                        </p>
                                        <p class="mb-2">
                                            <i class="fa-solid fa-building me-1 text-success"></i><?= $theater_name ?>
                                        </p>
                                        <p class="mb-2">
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">
                                                </i><?= $seat_row ?> - <?= $total_seat ?>
                                            </span>
                                        </p>
                                        <p class="fw-semibold text-success mb-3">
                                            <i class="fa-solid fa-indian-rupee-sign me-1"></i><?= $amount ?>
                                        </p>
                                        <div class="mb-2">
                                            <span class="badge <?= $method_badge ?> px-3 py-2 fw-semibold me-1">
                                                <i class="fa-solid fa-wallet me-1"></i><?= $payment_method ?>
                                            </span>
                                            <span class="badge <?= $payment_badge ?> px-3 py-2 fw-semibold">
                                                <?= $payment_status ?>
                                            </span>
                                        </div>
                                        <div class="mt-auto pt-2">
                                            <?php if ($booking_status === 'Pending'): ?>
                                                <div class="d-flex justify-content-between gap-1">
                                                    <a href="?action=approve&id=<?= $booking_id ?>" class="text-success text-decoration-none fw-semibold">
                                                        <i class="fa-solid fa-check me-1"></i> Approve
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted ">
                                                    <i class="fa-solid fa-circle-check me-1"></i>No Actions Available
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light border text-center py-4 mt-4">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        <span class="text-muted">No bookings found.</span>
                    </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Bookings pagination">
                        <ul class="pagination justify-content-center mt-5">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $page > 1 ? '?page=' . ($page - 1) : '#' ?>">
                                    &laquo; Prev
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $page < $total_pages ? '?page=' . ($page + 1) : '#' ?>">
                                    Next &raquo;
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </section>
        </section>
    </main>

</body>

</html>