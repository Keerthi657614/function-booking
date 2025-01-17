<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Function Hall Booking</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <h1>Book Your Function Hall</h1>
    <form id="bookingForm" action="booking.php" method="POST">
        <fieldset>
            <h1>Booking Information</h1>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required pattern="[A-Za-z ]+" title="Only letters and spaces are allowed."><br><br>

            <label for="functionType">Type of Function:</label>
            <input type="text" id="functionType" name="functionType" required><br><br>

            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" name="startDate" required><br><br>

            <label for="startTime">Start Time:</label>
            <input type="time" id="startTime" name="startTime" required><br><br>

            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" name="endDate" required><br><br>

            <label for="endTime">End Time:</label>
            <input type="time" id="endTime" name="endTime" required><br><br>

            <button type="submit">Check Availability & Book</button>
        </fieldset>
    </form>
    <p id="result">
        <?php
            if (isset($_GET['message'])) {
                echo htmlspecialchars($_GET['message']);
            }
        ?>
    </p>
</body>
</html>
