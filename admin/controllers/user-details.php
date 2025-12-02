<?php
require 'includes/dbconnection.php';

// ===== Pagination Settings =====
$limit = 4; // users per page (change if needed)
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $limit;

// ===== Total Users Count =====
$count_query  = "SELECT COUNT(*) AS total FROM users";
$count_result = mysqli_query($con, $count_query);
$total_records = 0;
if ($count_result && mysqli_num_rows($count_result) > 0) {
    $count_row = mysqli_fetch_assoc($count_result);
    $total_records = (int)$count_row['total'];
}
$total_pages = $total_records > 0 ? ceil($total_records / $limit) : 1;

// ===== Fetch Paginated Users =====
$sql_query = "
    SELECT * 
    FROM users
    ORDER BY created_at DESC
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($con, $sql_query);
if (!$result) {
    die("Query Error: " . mysqli_error($con));
}