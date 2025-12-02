<?php

require 'includes/dbconnection.php';

// ===== Pagination Settings =====
$limit = 4; // bookings per page
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $limit;

// ===== Total Bookings Count =====
$count_query  = "SELECT COUNT(*) AS total FROM bookings";
$count_result = mysqli_query($con, $count_query);
$total_records = 0;
if ($count_result && mysqli_num_rows($count_result) > 0) {
    $count_row = mysqli_fetch_assoc($count_result);
    $total_records = (int)$count_row['total'];
}
$total_pages = $total_records > 0 ? ceil($total_records / $limit) : 1;

// ===== Fetch bookings along with payment info (paginated) =====
$query = "
    SELECT 
        b.*, 
        u.username, 
        m.title, 
        s.show_date, 
        s.show_time, 
        t.theater_name,
        p.payment_method,
        p.payment_status,
        p.payment_message
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN movies_details m ON b.movie_id = m.movie_id
    JOIN showtimes s ON b.show_id = s.show_id
    JOIN theaters t ON s.theater_id = t.theater_id
    LEFT JOIN payments p ON b.booking_id = p.booking_id
    ORDER BY b.booking_id DESC
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($con, $query);
if (!$result) {
    die("Query Error: " . mysqli_error($con));
}