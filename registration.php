<!-- Pravin Mark Jayasinghe -->
<!-- 104182850 -->
<!-- COS80021: Developing Web Applications -->
<!-- Project 1 -->

<!-- registration.php: Invites client to sign up to CabsOnline -->
<?php

// Starting the session
session_start();

require_once 'settings.php';

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $customer_name = $_POST['customer_name'];
    $password = $_POST['password'];
    $reentered_password = $_POST['reentered_password'];
    $phone_number = $_POST['phone_number'];

    if ($password !== $reentered_password) {
        $message = "Passwords do not match.";
    } else {
        $stmt = $connection->prepare("SELECT email FROM customer WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = "Email already registered.";
        } else {
            $stmt = $connection->prepare("INSERT INTO customer (email, customer_name, password, phone_number) VALUES (:email, :customer_name, :password, :phone_number)");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":customer_name", $customer_name);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":phone_number", $phone_number);

            if ($stmt->execute()) {
                // Setting the session variables after successful registration
                $_SESSION['email'] = $email;
                $_SESSION['customer_name'] = $customer_name;

                header("Location: booking.php");
                exit();
            } else {
                $message = "Error during registration.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>CabsOnline Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://indestructibletype.com/fonts/Jost.css" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="text-center">Register for CabsOnline</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <form action="registration.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Name:</label>
                                <input type="text" id="customer_name" name="customer_name" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="reentered_password" class="form-label">Re-enter Password:</label>
                                <input type="password" id="reentered_password" name="reentered_password"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Contact Phone:</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                            <div class="mt-3">
                                <p>Already have an account? <a href="login.php">Login here</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>

</body>

</html>