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
    <title>MovieMate - Add Theater Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="../assets/img/favicon.svg" type="image/x-icon">
</head>

<body>

    <main class="d-flex">

        <!-- Navbar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Add Movie Form -->
        <section class="content-area flex-grow-1" style="margin-left: 260px; padding: 20px;">
            <section class="container my-3 px-4" style="width: 80rem;">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header text-center text-white py-4 admin-header">
                        <h1 class="fw-bold text-uppercase mb-0">
                            <i class="fa-solid fa-circle-plus me-2"></i> Add New Theater
                        </h1>
                    </div>
                    <div class="card-body p-4 p-md-5 bg-light">
                        <form action="controllers/add-theater.php" method="post" class="needs-validation" novalidate>
                            <div class="row g-4">

                                <!-- Theater Name -->
                                <div class="col-md-6">
                                    <label for="theater_name" class="form-label fw-semibold">Theater Name:</label>
                                    <input type="text" class="form-control" id="theater_name" name="theater_name"
                                        placeholder="Enter theater name..." required>
                                    <div class="invalid-feedback">Please enter the theater name.</div>
                                </div>

                                <!-- Theater Location -->
                                <div class="col-md-6">
                                    <label for="theater_location" class="form-label fw-semibold">Theater Location:</label>
                                    <input type="text" class="form-control" id="theater_location" name="theater_location"
                                        placeholder="Enter theater location..." required>
                                    <div class="invalid-feedback">Please enter the theater location.</div>
                                </div>

                                <!-- Ticket Price -->
                                <div class="col-md-6">
                                    <label for="ticket_price" class="form-label fw-semibold">Ticket Price (₹):</label>
                                    <input type="number" step="0.01" min="1" class="form-control" id="ticket_price"
                                        name="ticket_price" placeholder="Enter ticket price..." required>
                                    <div class="invalid-feedback">Please enter valid ticket price.</div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="text-center mt-5 d-flex flex-column flex-md-row gap-3 justify-content-center">
                                <button type="submit" class="bg-success text-white rounded border-0 w-100 w-md-auto px-5 py-2 fw-semibold shadow-sm">
                                    <i class="fa-solid fa-plus me-2"></i> Add Theater
                                </button>

                                <a href="manage_theaters.php" class="bg-danger text-white rounded w-100 w-md-auto px-5 py-2 fw-semibold shadow-sm text-decoration-none">
                                    <i class="fa-solid fa-xmark me-2"></i> Cancel
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </section>
        </section>
    </main>

    <script>
        document.querySelector("form").addEventListener("submit", function(e) {

            let isValid = true;

            // Ticket price validation
            const priceInput = document.getElementById("ticket_price");
            if (priceInput) {
                const price = parseFloat(priceInput.value);

                if (isNaN(price) || price < 100) {
                    priceInput.classList.add("is-invalid");
                    isValid = false;
                } else {
                    priceInput.classList.remove("is-invalid");
                }
            }

            if (!isValid) {
                e.preventDefault(); // stop form submission
            }
        });
    </script>


</body>

</html>