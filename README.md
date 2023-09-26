# CabsOnline

CabsOnline is a web application that allows users to book cabs online. It is built using PHP and MySQL for the backend, and HTML, CSS, and JavaScript for the frontend with Bootstrap5 for interface elements. I have used the Jost font throughout the site.

# Author

Pravin Mark Jayasinghe
104182850
for COS80021: Developing Web Applications

## Features

- User registration and login.
- Booking a cab at a future date for either yourself or on behalf of another.
- Receiving an email from CabsOnline confirming the booking details.
- Viewing and cancelling bookings.
- Allows admin to view all bookings, all bookings within 3 hours, and all bookings according to its status (assigned or unassigned)

## Installation

1. How to install CabsOnline:
   - upload all files (except the .txt files and this readme) to your server.
2. Create a MySQL database then create two tables using the customer.txt and booking.txt files, which contain the required SQL code. Use customer.txt first as booking.txt references the code in customer.txt. You can use the file dummy_customer_data.txt to create dummy records in your database for testing purposes.
3. Update the database connection details in settings.php if you are using your own database
4. I recommend using mercury server to host your files. It has PHP installed so it'll make usage of these files hassle-free.

## List of All Files (Alphabetical order)

- admin.php
- booking.php
- booking.txt
- customer.txt
- dummy_customer_data.txt
- index.php
- login.php
- README.md
- registration.php
- settings.php
- style.css

## Usage

1. Register a new user account
2. Login to your account (user will be automatically logged in upon successful registration)
3. Book a cab using the form provided
4. View your bookings and cancel them if necessary

## Database Details

$host = "feenix-mariadb.swin.edu.au";
$dbname = "s104182850_db";
$username = "s104182850";
$password = "**\*\*\*\***";

## Links

Use the links below to access CabsOnline hosted on my own mercury server via Swinburne University of Technology:

### Home page

https://mercury.swin.edu.au/cos80021/s104182850/Project1/index.php

### Login

https://mercury.swin.edu.au/cos80021/s104182850/Project1/login.php

test account email : pravin@pravin.com
test account password: 123

### Register

https://mercury.swin.edu.au/cos80021/s104182850/Project1/registration.php

### Booking

https://mercury.swin.edu.au/cos80021/s104182850/Project1/booking.php

^ This is accessible only when logged in

### Admin

https://mercury.swin.edu.au/cos80021/s104182850/Project1/admin.php

## Notes

Made with ♥︎ in Hawthorn, Melbourne
