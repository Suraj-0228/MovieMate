<?php
session_start();

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
    <title>MovieMate - Update Theater</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="../assets/img/favicon.svg" type="image/x-icon">
</head>

<body>

    <main class="d-flex">

        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Update Theater Section -->
        <section class="content-area flex-grow-1" style="margin-left: 260px; padding: 20px;">
            <section class="container my-3 px-4" style="width: 80rem;">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header text-center text-white py-4 admin-header">
                        <h1 class="fw-bold text-uppercase mb-0">
                            <i class="fa-solid fa-building me-2"></i> Update Theater Details
                        </h1>
                    </div>

                    <div class="card-body p-4 p-md-5 bg-light">
                        <?php
                        require 'includes/dbconnection.php';

                        $id = null;
                        $theater_name = $theater_location = '';
                        $ticket_price = '';

                        if (isset($_GET['theater_id']) && is_numeric($_GET['theater_id'])) {
                            $id = (int) $_GET['theater_id'];
                            $sql_query = "SELECT * FROM theaters WHERE theater_id = $id";
                            $result = mysqli_query($con, $sql_query);

                            if ($rows = mysqli_fetch_assoc($result)) {
                                $theater_name     = $rows['theater_name'];
                                $theater_location = $rows['theater_location'];
                                $ticket_price     = $rows['ticket_price'];
                            } else {
                                echo "<div class='alert alert-danger'>Theater not found.</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Invalid theater request.</div>";
                        }
                        ?>

                        <?php if ($id !== null): ?>
                            <form action="controllers/update_theater.php" method="post" class="needs-validation" novalidate>
                                <input type="hidden" name="theater_id" value="<?= $id ?>">

                                <div class="row g-4">
                                    <!-- Theater Name -->
                                    <div class="col-md-6">
                                        <label for="theater_name" class="form-label fw-semibold">Theater Name:</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="theater_name"
                                            name="theater_name"
                                            placeholder="Enter theater name..."
                                            value="<?= htmlspecialchars($theater_name) ?>"
                                            required>
                                        <div class="invalid-feedback">Please enter the theater name.</div>
                                    </div>

                                    <!-- Location -->
                                    <div class="col-md-6">
                                        <label for="theater_location" class="form-label fw-semibold">Location:</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="theater_location"
                                            name="theater_location"
                                            placeholder="Enter theater location..."
                                            value="<?= htmlspecialchars($theater_location) ?>"
                                            required>
                                        <div class="invalid-feedback">Please enter the theater location.</div>
                                    </div>

                                    <!-- Ticket Price -->
                                    <div class="col-md-6">
                                        <label for="ticket_price" class="form-label fw-semibold">Ticket Price (₹):</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="ticket_price"
                                            name="ticket_price"
                                            step="1"
                                            min="50"
                                            max="1000"
                                            value="<?= htmlspecialchars($ticket_price) ?>"
                                            required>
                                        <div class="invalid-feedback">Please enter a valid ticket price.</div>
                                    </div>
                                </div>

                                <div class="text-center mt-5 d-flex flex-column flex-md-row gap-3 justify-content-center">
                                    <button
                                        type="submit"
                                        class="bg-success px-5 py-2 text-white border border-0 rounded w-100 fw-semibold">
                                        <i class="fa-solid fa-upload me-2"></i> Update Theater
                                    </button>
                                    <a href="manage_theaters.php"
                                        class="bg-danger text-white rounded w-100 w-md-auto px-5 py-2 fw-semibold shadow-sm text-decoration-none">
                                        <i class="fa-solid fa-xmark me-2"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <script>
        // Bootstrap client-side validation
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>

</body>

</html>