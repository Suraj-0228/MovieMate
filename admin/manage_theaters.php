<?php
session_start();

require 'controllers/theater-details.php';
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
    <title>MovieMate - Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="../assets/img/favicon.svg" type="image/x-icon">
</head>

<body>

    <main class="d-flex">

        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Manage Users Section -->
        <section class="content-area flex-grow-1" style="margin-left: 260px; padding: 20px;">
            <section class="container my-3 px-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                    <h1 class="fw-bold text-primary text-uppercase mb-3 mb-md-0">
                        <i class="fa-solid fa-display me-2"></i> Manage Theaters
                    </h1>
                    <a href="add_theater.php" class="bg-success text-white rounded border-0 w-md-auto px-5 py-2 fw-semibold shadow-sm text-decoration-none">
                        <i class="fa-solid fa-plus me-2"></i> Add Theater </a>
                </div>
                <div class="row g-4">
                    <?php while ($row = mysqli_fetch_assoc($result)):
                        $id       = $row['theater_id'];
                        $name     = htmlspecialchars($row['theater_name']);
                        $location = htmlspecialchars($row['theater_location']);
                        $price    = number_format($row['ticket_price'], 2);
                    ?>
                        <!-- 4 cards per row -->
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden my-3">
                                <div class="card-body d-flex flex-column p-4">

                                    <!-- Theater Name -->
                                    <h5 class="card-title fw-bold text-truncate" title="<?= $name ?>">
                                        <i class="fa-solid fa-building me-2 text-primary"></i><?= $name ?>
                                    </h5>

                                    <!-- Location -->
                                    <p class="mb-2 small text-muted">
                                        <i class="fa-solid fa-location-dot me-2 text-danger"></i>
                                        <?= $location ?>
                                    </p>

                                    <!-- Price -->
                                    <p class="mb-3">
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            ₹ <?= $price ?>
                                        </span>
                                    </p>

                                    <!-- Actions -->
                                    <div class="d-flex gap-3 mt-auto">
                                        <a href="update_theater.php?theater_id=<?= $id ?>"
                                            class="text-success text-decoration-none"
                                            title="Edit Theater">
                                            <i class="fa-solid fa-pen-to-square fa-lg"></i> Edit
                                        </a>

                                        <a href="controllers/delete_theater.php?theater_id=<?= $id ?>"
                                            class="text-danger text-decoration-none"
                                            onclick="return confirm('Are you sure you want to delete this theater?');"
                                            title="Delete Theater">
                                            <i class="fa-solid fa-trash fa-lg"></i> Delete
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Theaters pagination">
                        <ul class="pagination justify-content-center mt-5">

                            <!-- Prev -->
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $page > 1 ? '?page=' . ($page - 1) : '#' ?>">
                                    &laquo; Prev
                                </a>
                            </li>

                            <!-- Page numbers -->
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next -->
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