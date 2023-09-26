<!-- Pravin Mark Jayasinghe -->
<!-- 104182850 -->
<!-- COS80021: Developing Web Applications -->
<!-- Project 1 -->

<!-- booking.php: Allows client to make bookings -->

<?php

session_start();

if (!isset($_SESSION['customer_name']) || !isset($_SESSION['email'])) {
    // Redirect back to login page or show an error
    die('Please log in first.');
}

$customer_name = $_SESSION['customer_name'];
$email_address = $_SESSION['email'];

require_once 'settings.php';


$message = "";

// connect to db
try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $passenger_name = $_POST['passenger_name'];
    $passenger_phone_number = $_POST['passenger_phone_number'];
    $unit_number = $_POST['unit_number'];
    $street_number = $_POST['street_number'];
    $street_name = $_POST['street_name'];
    $suburb = $_POST['suburb'];
    $destination_suburb = $_POST['destination_suburb'];
    $pickup_date = $_POST['pickup_date'];
    $pickup_time = $_POST['pickup_time'];

    $pickup_datetime = new DateTime($pickup_date . ' ' . $pickup_time);
    $current_datetime = new DateTime();
    $diff = $pickup_datetime->diff($current_datetime);

    if ($diff->i < 40 && $diff->h == 0 && $diff->days == 0) {
        $message = "Please enter a pickup time and date at least 40 minutes in the future.";
    } else {
        $booking_number = uniqid("BR");

        $stmt = $connection->prepare("INSERT INTO booking (booking_number, email_address, passenger_name, passenger_phone_number, unit_number, street_number, street_name, suburb, destination_suburb, pickup_date, pickup_time, status) VALUES (:booking_number, :email_address, :passenger_name, :passenger_phone_number, :unit_number, :street_number, :street_name, :suburb, :destination_suburb, :pickup_date, :pickup_time, 'unassigned')");

        $stmt->bindParam(':booking_number', $booking_number);
        $stmt->bindParam(':email_address', $email_address);
        $stmt->bindParam(':passenger_name', $passenger_name);
        $stmt->bindParam(':passenger_phone_number', $passenger_phone_number);
        $stmt->bindParam(':unit_number', $unit_number);
        $stmt->bindParam(':street_number', $street_number);
        $stmt->bindParam(':street_name', $street_name);
        $stmt->bindParam(':suburb', $suburb);
        $stmt->bindParam(':destination_suburb', $destination_suburb);
        $stmt->bindParam(':pickup_date', $pickup_date);
        $stmt->bindParam(':pickup_time', $pickup_time);

        if ($stmt->execute()) {
            $message = "Booking successfully saved! 😀 See you soon!";

            // send mail to client
            $to = $email_address;
            $subject = "CabsOnline Booking Confirmation";
            $message = "Booking successfully made! 😀 We'll see you soon! Your booking reference number is " . $booking_number . " and we'll pick you up at " . $pickup_time . " on " . $pickup_date . ".";
            $headers = "From: CabsOnline <cabsonline@cabsonline.com>";

            if (mail($to, $subject, $message, $headers)) {
                $message = "Booking successfully saved! 😀 We'll see you soon! A confirmation email has been sent to " . $email_address . ".";
            } else {
                $message = "Booking successfully saved! 😀 We'll see you soon! There was an error sending a confirmation email to " . $email_address . ".";
            }
        } else {
            $message = "Error saving booking.";
        }
    }
}
?>

<!--interface for client-->
<!DOCTYPE html>
<html>

<head>
    <title>CabsOnline Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://indestructibletype.com/fonts/Jost.css" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-2">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="text-center">Hello
                            <?php echo htmlspecialchars($customer_name); ?>! Please make a booking
                        </h3>
                        <p class="text-center">Your email address is:
                            <?php echo htmlspecialchars($email_address); ?>
                        </p>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-warning">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                        <form action="booking.php" method="post">
                            <div class="mb-3">
                                <label for="passenger_name" class="form-label">Passenger Name:</label>
                                <input type="text" id="passenger_name" name="passenger_name" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="passenger_phone_number" class="form-label">Passenger Phone Number:</label>
                                <input type="tel" id="passenger_phone_number" name="passenger_phone_number"
                                    class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="unit_number" class="form-label">Unit Number (optional):</label>
                                <input type="text" id="unit_number" name="unit_number" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="street_number" class="form-label">Street Number:</label>
                                <input type="text" id="street_number" name="street_number" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="street_name" class="form-label">Street Name:</label>
                                <input type="text" id="street_name" name="street_name" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="suburb" class="form-label">Suburb:</label>
                                        <input type="text" id="suburb" name="suburb" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="destination_suburb" class="form-label">Destination Suburb:</label>
                                        <input type="text" id="destination_suburb" name="destination_suburb"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="pickup_date" class="form-label">Pickup Date:</label>
                                        <input type="date" id="pickup_date" name="pickup_date" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="pickup_time" class="form-label">Pickup Time:</label>
                                        <input type="time" id="pickup_time" name="pickup_time" class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Submit Booking</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>