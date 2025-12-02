<?php
require 'controllers/booking-process2.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMate - Ticket Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/booking.css" />
    <link rel="stylesheet" href="assets/css/footer.css" />
    <link rel="shortcut icon" href="assets/img/favicon.svg" type="image/x-icon">
</head>

<body>

    <!-- Navbar -->
    <?php include 'includes/header.php'; ?>

    <!-- Booking Form -->
    <div class="container my-5">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="booking-header text-center py-4">
                <h1 class="fw-bold mb-1">Book Your Tickets Now!</h1>
                <p class="text-light opacity-75 mb-0">
                    Choose your date, showtime, seats and complete your booking in a few steps.
                </p>
            </div>
            <div class="card-body p-4 p-md-5 bg-light">
                <div class="row g-4 align-items-start">
                    <div class="col-lg-5">
                        <form action="controllers/booking-process.php" method="post" id="bookingForm" class="booking-form">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <h5 class="mb-0 fw-semibold">Date & Show</h5>
                                </div>
                                <small class="text-muted">Confirm the movie, date and showtime.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Movie Title:</label>
                                <input type="hidden" name="movie_id" value="<?= $movie_id; ?>">
                                <input
                                    type="text"
                                    class="form-control border-0 shadow-sm bg-white"
                                    value="<?= $movie_title; ?>"
                                    readonly>
                            </div>
                            <div class="row g-3 mb-4">
                                <?php $today = date("Y-m-d"); ?>
                                <div class="col-12">
                                    <label for="booking_date" class="form-label fw-semibold">Select Date:</label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-0">
                                            <i class="fa-regular fa-calendar-days text-primary"></i>
                                        </span>
                                        <input
                                            type="date"
                                            class="form-control border-0"
                                            name="booking_date"
                                            id="booking_date"
                                            required
                                            min="<?= $today; ?>">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="show_id" class="form-label fw-semibold">Select Show:</label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-0">
                                            <i class="fa-regular fa-clock text-primary"></i>
                                        </span>
                                        <select
                                            class="form-select border-0"
                                            name="show_id"
                                            id="show_id"
                                            required>
                                            <option value="">Select Date, Time &amp; Theater</option>
                                            <?php foreach ($shows as $show) {
                                                $selected  = ($selected_show_id == $show['show_id']) ? 'selected' : '';
                                                $show_data = date("h:i A", strtotime($show['show_time']))
                                                    . ' || ' . $show['theater_name']
                                                    . ' (' . $show['theater_location'] . ')';
                                                echo "<option value='{$show['show_id']}' data-price='{$show['ticket_price']}' $selected>$show_data</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr class="border-dark opacity-50">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <h5 class="mb-0 fw-semibold">Payment</h5>
                                </div>
                                <small class="text-muted">Check price and total amount before payment.</small>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label for="ticketPrice" class="form-label fw-semibold">Ticket Price:</label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-0">₹</span>
                                        <input
                                            type="number"
                                            class="form-control border-0"
                                            name="ticketPrice"
                                            id="ticketPrice"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="amount" class="form-label fw-semibold">Total Amount:</label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-0">₹</span>
                                        <input
                                            type="number"
                                            class="form-control border-0"
                                            name="amount"
                                            id="amount"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 mb-2 d-flex flex-column gap-3">
                                <button
                                    type="button"
                                    class="bg-success px-5 py-2 text-white border-0 rounded w-100 fw-bold shadow-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#paymentModal">
                                    <i class="fa-solid fa-wallet me-2"></i> Pay &amp; Book Now
                                </button>
                                <button
                                    type="button"
                                    class="bg-danger text-white rounded w-100 px-5 py-2 fw-semibold border-0 shadow-sm"
                                    onclick="window.history.back()">
                                    <i class="fa-solid fa-xmark me-2"></i> Cancel
                                </button>
                            </div>
                            <input type="hidden" name="seatRow" id="seatRow">
                            <input type="hidden" name="totalSeat" id="totalSeat">
                            <input type="hidden" name="payment_method" id="payment_method" required>
                        </form>
                    </div>
                    <div class="col-lg-7">
                        <div class="seat-layout border rounded-4 bg-white p-3 p-md-4 h-100">                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 fw-semibold">Seat Selection</h5>
                                <small class="text-muted">Tap to select / deselect</small>
                            </div>
                            <hr class="border-dark opacity-50">
                            <div class="seat-section mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold text-uppercase small">Recliner</span>
                                    <small class="text-muted">Row A</small>
                                </div>
                                <div class="seat-row d-flex justify-content-center flex-wrap gap-1">
                                    <?php for ($i = 1; $i <= 16; $i++): ?>
                                        <button
                                            type="button"
                                            class="seat"
                                            data-row="A"
                                            data-seat="<?= $i; ?>">
                                            <?= str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                        </button>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="seat-section mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold text-uppercase small">Executive</span>
                                    <small class="text-muted">Rows B–F</small>
                                </div>
                                <?php foreach (['B', 'C', 'D', 'E', 'F'] as $rowLabel): ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="seat-row-label me-2 fw-semibold small"><?= $rowLabel; ?></div>
                                        <div class="seat-row d-flex justify-content-center flex-wrap gap-1 flex-grow-1">
                                            <?php for ($i = 1; $i <= 16; $i++): ?>
                                                <button
                                                    type="button"
                                                    class="seat"
                                                    data-row="<?= $rowLabel; ?>"
                                                    data-seat="<?= $i; ?>">
                                                    <?= str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                                </button>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="d-flex flex-wrap justify-content-center gap-3 mt-3 small">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="seat legend available"></span> Available
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="seat legend selected"></span> Selected
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="seat legend booked"></span> Booked
                                </div>
                            </div>
                            <div class="text-center mt-3 small text-muted">
                                Selected seats:
                                <span id="selectedSeatsLabel" class="fw-semibold">None</span>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>

            <!-- Payment Method Modal (unchanged) -->
            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg border-0 rounded-4">
                        <div class="modal-header payment-header text-white">
                            <h5 class="modal-title" id="paymentModalLabel">
                                <i class="fa-solid fa-credit-card me-2"></i> Select Payment Method
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="list-group">
                                <button type="button" class="list-group-item list-group-item-action payment-option" data-method="UPI">
                                    <i class="fa-brands fa-google-pay me-2 text-success"></i> Pay via UPI
                                </button>
                                <button type="button" class="list-group-item list-group-item-action payment-option" data-method="Card">
                                    <i class="fa-solid fa-credit-card me-2 text-primary"></i> Pay via Card
                                </button>
                                <button type="button" class="list-group-item list-group-item-action payment-option" data-method="Cash">
                                    <i class="fa-solid fa-money-bill me-2 text-success"></i> Cash at Counter
                                </button>
                            </div>
                            <div id="upiForm" class="payment-form mt-4 d-none text-center">
                                <p class="fw-semibold mb-2">Scan the QR Code Below to Pay:</p>
                                <img
                                    src="assets/img/QR Scanner.jpg"
                                    alt="UPI QR Code"
                                    class="img-fluid rounded shadow-sm border mb-3"
                                    style="max-width: 180px;">
                                <p class="text-muted mb-3">Or enter your UPI ID manually:</p>
                                <input type="text" class="form-control mb-3" id="upiIdInput" placeholder="example@upi">
                                <p id="upiError" class="text-danger fw-semibold mt-2 d-none"></p>
                                <button
                                    type="button"
                                    class="bg-success px-5 py-2 text-white border-0 rounded w-100 fw-bold"
                                    id="upiPayBtn">
                                    Pay Now
                                </button>
                            </div>
                            <div id="cardForm" class="payment-form d-none mt-4">
                                <input type="text" class="form-control mb-2" id="cardNumberInput" placeholder="Card Number (16 digits)">
                                <input type="text" class="form-control mb-2" id="cardHolderInput" placeholder="Card Holder Name">
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="text" class="form-control mb-2" id="cardExpiryInput" placeholder="MM/YY">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control mb-2" id="cardCVVInput" placeholder="CVV">
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="bg-success px-5 py-2 text-white border-0 rounded w-100 fw-bold"
                                    id="cardPayBtn">
                                    Pay Now
                                </button>
                            </div>
                            <div id="cashForm" class="payment-form d-none mt-4 text-center">
                                <p class="text-muted mb-3">
                                    Please pay the cash amount at the counter within 1 hour to confirm your booking.
                                </p>
                                <button
                                    type="button"
                                    class="bg-danger px-5 py-2 text-white border-0 rounded w-100 fw-bold"
                                    id="cashConfirmBtn">
                                    Confirm Cash Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/booking.js"></script>

</body>

</html>