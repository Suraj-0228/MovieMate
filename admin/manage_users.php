<?php
session_start();

require 'controllers/user-details.php';
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
                <div class="d-flex flex-column text-primary flex-md-row justify-content-between align-items-center mb-4">
                    <h1 class="fw-bold text-uppercase mb-3 mb-md-0">
                        <i class="fa-solid fa-users me-2"></i> Manage Users
                    </h1>
                </div>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <div class="row g-4">
                        <?php while ($rows = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $user_id       = $rows['user_id'];
                            $fullname      = $rows['fullname'];
                            $email         = $rows['email'];
                            $username      = $rows['username'];
                            $user_password = $rows['user_password'];
                            $created_at    = $rows['created_at'];
                            ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                    <div class="card-header admin-header text-white d-flex justify-content-between align-items-center">
                                        <span class="">
                                            <i class="fa-solid fa-id-badge me-1"></i>User ID: #<?= $user_id ?>
                                        </span>
                                        <span class="">
                                            <i class="fa-solid fa-calendar-days me-1"></i>
                                            <?= date('d M Y', strtotime($created_at)) ?>
                                        </span>
                                    </div>
                                    <div class="card-body d-flex flex-column p-4">
                                        <h5 class="card-title fw-bold mb-2 text-truncate" title="<?= htmlspecialchars($fullname) ?>">
                                            <i class="fa-solid fa-user me-2 text-primary"></i><?= htmlspecialchars($fullname) ?>
                                        </h5>
                                        <p class="mb-2 text-muted text-truncate" title="<?= htmlspecialchars($email) ?>">
                                            <i class="fa-solid fa-envelope me-1"></i><?= htmlspecialchars($email) ?>
                                        </p>
                                        <p class="mb-2">
                                            <i class="fa-solid fa-at me-1 text-primary"></i><?= htmlspecialchars($username) ?>
                                        </p>
                                        <p class="mb-2">
                                            <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle rounded-pill px-3 py-2">
                                                <i class="fa-solid fa-lock me-1"></i>
                                                <?= htmlspecialchars($user_password) ?>
                                            </span>
                                        </p>
                                        <p class="mb-2 text-muted">
                                            <i class="fa-solid fa-clock me-1"></i>
                                            <?= date('d M Y, h:i A', strtotime($created_at)) ?>
                                        </p>
                                        <div class="mt-auto pt-2 d-flex justify-content-end">
                                            <a href="controllers/delete-users.php?user_id=<?= $user_id ?>" class="text-danger text-decoration-none fw-semibold"
                                                onclick="return confirm('Are you sure you want to delete this user?');">
                                                <i class="fa-solid fa-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light border text-center py-4 mt-4">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        <span class="text-muted">No users found.</span>
                    </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Users pagination">
                        <ul class="pagination justify-content-center mt-4">
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