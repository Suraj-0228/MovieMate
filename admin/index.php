<?php
session_start();
require 'includes/dbconnection.php';

// If session not set but cookie exists, restore session
if (!isset($_SESSION['adminname']) && isset($_COOKIE['adminname'])) {
    $_SESSION['adminname'] = $_COOKIE['adminname'];
}

// If neither session nor cookie, redirect to login
if (!isset($_SESSION['adminname'])) {
    echo "<script>
        alert('Please, Login to Access MovieMate Admin!!');
        window.location.href = '../login.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMate - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="../assets/img/favicon.svg" type="image/x-icon">
</head>

<body>

    <main class="d-flex">

        <!-- Navbar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Admin Dashboard Section -->
        <section class="content-area flex-grow-1" style="margin-left: 260px; padding: 20px;">
            <section class="container my-3 px-4" style="width: 80rem;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                    <h1 class="fw-bold text-primary text-uppercase mb-3 mb-md-0">
                        <i class="fa-solid fa-user-tie me-2"></i> Admin Dashboard
                    </h1>
                </div>
                <div class="row g-4 text-center">
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card shadow border-0 rounded h-100">
                            <div class="card-body p-5 d-flex flex-column justify-content-center align-items-center">
                                <i class="fa fa-film fa-2xl text-primary mb-4"></i>
                                <h5 class="fw-bold mb-2">Total Movies</h5>
                                <?php
                                require 'includes/dbconnection.php';
                                $result = mysqli_query($con, "SELECT COUNT(*) AS total_movies FROM movies_details");
                                $row = mysqli_fetch_assoc($result);
                                ?>
                                <p class="fs-4 fw-semibold mb-0 text-dark"><?= $row['total_movies'] ?? '0'; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card shadow border-0 rounded h-100">
                            <div class="card-body p-5 d-flex flex-column justify-content-center align-items-center">
                                <i class="fa fa-ticket fa-2xl text-success mb-4"></i>
                                <h5 class="fw-bold mb-2">Total Bookings</h5>
                                <?php
                                $result = mysqli_query($con, "SELECT COUNT(*) AS total_booking FROM bookings");
                                $row = mysqli_fetch_assoc($result);
                                ?>
                                <p class="fs-4 fw-semibold mb-0 text-dark"><?= $row['total_booking'] ?? '0'; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card shadow border-0 rounded h-100">
                            <div class="card-body p-5 d-flex flex-column justify-content-center align-items-center">
                                <i class="fa fa-user fa-2xl text-danger mb-4"></i>
                                <h5 class="fw-bold mb-2">Total Users</h5>
                                <?php
                                $result = mysqli_query($con, "SELECT COUNT(*) AS total_user FROM users");
                                $row = mysqli_fetch_assoc($result);
                                ?>
                                <p class="fs-4 fw-semibold mb-0 text-dark"><?= $row['total_user'] ?? '0'; ?></p>
                            </div>
                        </div>
                    </div>
                    <hr class="border border-dark my-5 opacity-75">
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                    <h1 class="fw-bold text-primary text-uppercase mb-3 mb-md-0">
                        <i class="fa-solid fa-ticket me-2"></i> Recent Bookings
                    </h1>
                </div>

                <?php
                $query = "
                        SELECT 
                            b.*, 
                            u.username, 
                            m.title, 
                            s.show_date, 
                            s.show_time, 
                            t.theater_name,
                            p.payment_method,
                            p.payment_status
                        FROM bookings b
                        JOIN users u ON b.user_id = u.user_id
                        JOIN movies_details m ON b.movie_id = m.movie_id
                        JOIN showtimes s ON b.show_id = s.show_id
                        JOIN theaters t ON s.theater_id = t.theater_id
                        LEFT JOIN payments p ON b.booking_id = p.booking_id
                        ORDER BY b.booking_id DESC";
                $result_recent = mysqli_query($con, $query);
                ?>

                <?php if (mysqli_num_rows($result_recent) > 0) : ?>
                    <div class="table-responsive shadow-sm rounded">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Booking ID</th>
                                    <th>User</th>
                                    <th>Movie</th>
                                    <th>Show Date</th>
                                    <th>Show Time</th>
                                    <th>Theater</th>
                                    <th>Seat</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php while ($row = mysqli_fetch_assoc($result_recent)) : ?>
                                    <?php
                                    $status = $row['booking_status'];
                                    $status_badge = match ($status) {
                                        'Pending' => 'bg-warning text-dark',
                                        'Approved' => 'bg-success',
                                        default => 'bg-danger'
                                    };

                                    $payment_method = $row['payment_method'] ?? 'N/A';
                                    $payment_status = $row['payment_status'] ?? 'Pending';

                                    $payment_badge = match ($payment_status) {
                                        'Confirmed' => 'bg-success',
                                        'Pending' => 'bg-warning text-dark',
                                        'Failed' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };

                                    $payment_label = ($payment_method !== 'N/A') ? $payment_method : 'Not Paid';
                                    ?>
                                    <tr>
                                        <td><?= $row['booking_id']; ?></td>
                                        <td><?= htmlspecialchars($row['username']); ?></td>
                                        <td><?= htmlspecialchars($row['title']); ?></td>
                                        <td><?= $row['show_date']; ?></td>
                                        <td><?= date("h:i A", strtotime($row['show_time'])); ?></td>
                                        <td><?= htmlspecialchars($row['theater_name']); ?></td>
                                        <td><?= $row['seat_row']; ?> - <?= $row['total_seat']; ?></td>
                                        <td class="fw-semibold text-success">₹<?= number_format($row['amount'], 2); ?></td>
                                        <td><span class="badge <?= $status_badge; ?> px-3 py-2 fw-semibold"><?= $status; ?></span></td>
                                        <td>
                                            <span class="badge <?= $payment_badge; ?> px-3 py-2 fw-semibold">
                                                <?= $payment_label; ?> (<?= $payment_status; ?>)
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="text-center text-muted mt-4">No recent bookings found.</p>
                <?php endif; ?>
            </section>
        </section>
    </main>

</body>

</html>