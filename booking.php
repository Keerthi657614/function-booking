<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "function_hall_booking";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$functionType = $_POST['functionType'];
$startDate = $_POST['startDate'];
$startTime = $_POST['startTime'];
$endDate = $_POST['endDate'];
$endTime = $_POST['endTime'];

// Convert start and end date/time to datetime format for comparison
$startDateTime = "$startDate $startTime";
$endDateTime = "$endDate $endTime";

// Check if the selected time slot is already booked
$sql = "SELECT * FROM bookings WHERE 
            (start_date <= ? AND end_date >= ?) AND 
            ((start_time <= ? AND end_time >= ?) OR
             (start_time <= ? AND end_time >= ?))";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $startDate, $endDate, $startTime, $endTime, $startTime, $endTime);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If there's a booking conflict, display a message
    echo "<p>Sorry, the hall is already booked during that time.</p>";
} else {
    // Calculate price based on time duration
    $startDateTime = new DateTime("$startDate $startTime");
    $endDateTime = new DateTime("$endDate $endTime");
    $duration = $startDateTime->diff($endDateTime)->h;

    if ($duration < 5) {
        $price = 100;  // Price for less than 5 hours
    } elseif ($duration < 10) {
        $price = 200;  // Price for less than 10 hours
    } elseif ($duration < 15) {
        $price = 300;  // Price for less than 15 hours
    } else {
        $price = 400;  // Price for more than 15 hours
    }

    // Insert the booking into the database
    $sql = "INSERT INTO bookings (name, function_type, start_date, start_time, end_date, end_time, price) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssd", $name, $functionType, $startDate, $startTime, $endDate, $endTime, $price);

    if ($stmt->execute()) {
        // Display the estimation slip after successful booking
        echo "<div style='width: 80%; margin: 0 auto; padding: 20px; border: 2px solid #000; border-radius: 8px;'>";
        echo "<h2 style='text-align: center;'>Booking Confirmation</h2>";
        echo "<p><strong>Name:</strong> $name</p>";
        echo "<p><strong>Function Type:</strong> $functionType</p>";
        echo "<p><strong>Start Date & Time:</strong> $startDate $startTime</p>";
        echo "<p><strong>End Date & Time:</strong> $endDate $endTime</p>";
        echo "<p><strong>Total Duration:</strong> " . $duration . " hours</p>";
        echo "<p><strong>Total Price:</strong> $$price</p>";
        echo "<p><strong>Status:</strong> Booking Confirmed</p>";
        echo "<p style='text-align: center;'><strong>Thank you for booking with us!</strong></p>";
        echo "</div>";
    } else {
        echo "<p>Booking failed. Please try again.</p>";
    }
}

$stmt->close();
$conn->close();
?>
