<!-- Pravin Mark Jayasinghe -->
<!-- 104182850 -->
<!-- COS80021: Developing Web Applications -->
<!-- Project 1 -->

<!-- admin.php: Allows admin to view bookings -->
<?php
require_once 'settings.php';

$message = "";

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to list all unassigned bookings within the next 3 hours
if (isset($_POST['list_all'])) {
    $current_datetime = new DateTime();
    $current_datetime->modify('+3 hours');
    $end_time = $current_datetime->format('Y-m-d H:i:s');

    $stmt = $connection->prepare("SELECT * FROM booking WHERE status = 'unassigned' AND CONCAT(pickup_date, ' ', pickup_time) <= :end_time");
    $stmt->bindParam(':end_time', $end_time);
    $stmt->execute();
    $bookings = $stmt->fetchAll();
}

// Function to assign taxi to a booking
if (isset($_POST['assign_taxi'])) {
    $booking_number = $_POST['booking_number'];
    $stmt = $connection->prepare("UPDATE booking SET status = 'assigned' WHERE booking_number = :booking_number AND status = 'unassigned'");
    $stmt->bindParam(':booking_number', $booking_number);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        $message = "The booking request $booking_number has been properly assigned.";
    } else {
        $message = "No unassigned booking request matched for update.";
    }
}
// Function to search bookings by passenger name
if (isset($_POST['search_by_name'])) {
    $passenger_name = $_POST['passenger_name'];
    $stmt = $connection->prepare("SELECT * FROM booking WHERE passenger_name LIKE :passenger_name");
    $stmt->bindParam(':passenger_name', $passenger_name);
    $stmt->execute();
    $bookings = $stmt->fetchAll();
}

// Function to search bookings by status
if (isset($_POST['search_by_status'])) {
    $status = $_POST['status'];
    $stmt = $connection->prepare("SELECT * FROM booking WHERE status = :status");
    $stmt->bindParam(':status', $status);
    $stmt->execute();
    $bookings = $stmt->fetchAll();
}
// Function to list all bookings within the next 3 hours from now
if (isset($_POST['list_within_3_hours'])) {
    $current_datetime = new DateTime();
    $start_time = $current_datetime->format('Y-m-d H:i:s');
    $current_datetime->modify('+3 hours');
    $end_time = $current_datetime->format('Y-m-d H:i:s');

    $stmt = $connection->prepare("SELECT * FROM booking WHERE CONCAT(pickup_date, ' ', pickup_time) BETWEEN :start_time AND :end_time");
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->execute();
    $bookings = $stmt->fetchAll();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>CabsOnline Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://indestructibletype.com/fonts/Jost.css" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1>CabsOnline Admin System</h1>
        <?php if (!empty($message)): ?>
            <div class="alert alert-warning">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- List All button -->
        <form action="admin.php" method="post">
            <button type="submit" name="list_all" class="btn btn-primary">List All</button>
        </form>

        <!-- Booking assignment form -->
        <form action="admin.php" method="post" class="mt-4">
            <input type="text" name="booking_number" placeholder="Booking Reference">
            <button type="submit" name="assign_taxi" class="btn btn-secondary">Update Status to Assigned</button>
        </form>
        <!-- Search by Passenger Name -->
        <form action="admin.php" method="post" class="mt-4">
            <input type="text" name="passenger_name" placeholder="Search by Passenger Name">
            <button type="submit" name="search_by_name" class="btn btn-secondary">Search</button>
        </form>

        <!-- Search by Booking Status -->
        <form action="admin.php" method="post" class="mt-4">
            <select name="status">
                <option value="unassigned">Unassigned</option>
                <option value="assigned">Assigned</option>
            </select>
            <button type="submit" name="search_by_status" class="btn btn-secondary">Search by Status</button>
        </form>

        <!-- List all Bookings 3 hours from now -->
        <form action="admin.php" method="post" class="mt-4">
            <button type="submit" name="list_within_3_hours" class="btn btn-success">List all bookings within 3 hours
                from now</button>
        </form>

        <?php if (isset($bookings)): ?>
            <table class="table mt-5">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Status</th>
                        <th>Passenger Name</th>
                        <th>Contact Phone</th>
                        <th>Pick-up Address</th>
                        <th>Destination</th>
                        <th>Pick-up Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td>
                                <?php echo $booking['booking_number']; ?>
                            </td>
                            <td>
                                <?php echo $booking['status']; ?>
                            </td>
                            <td>
                                <?php echo $booking['passenger_name']; ?>
                            </td>
                            <td>
                                <?php echo $booking['passenger_phone_number']; ?>
                            </td>
                            <td>
                                <?php
                                echo ($booking['unit_number'] ? $booking['unit_number'] . "/" : "") .
                                    $booking['street_number'] . " " .
                                    $booking['street_name'] . ", " .
                                    $booking['suburb'];
                                ?>
                            </td>
                            <td>
                                <?php echo $booking['destination_suburb']; ?>
                            </td>
                            <td>
                                <?php echo $booking['pickup_date'] . " " . $booking['pickup_time']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>