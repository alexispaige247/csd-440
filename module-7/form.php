<!DOCTYPE html>
<html>
<head>
    <title>Form Result</title>
</head>
<body>

<?php

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get values
    $name = $_POST["name"];
    $email = $_POST["email"];
    $age = $_POST["age"];
    $password = $_POST["password"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $color = $_POST["color"];

    // Validation
    if (
        empty($name) || empty($email) || empty($age) ||
        empty($password) || empty($dob) || empty($gender) || empty($color)
    ) {
        echo "<h2 style='color:red;'>Error: All fields must be filled out!</h2>";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<h2 style='color:red;'>Error: Invalid email format!</h2>";
    }
    elseif (!is_numeric($age) || $age <= 0) {
        echo "<h2 style='color:red;'>Error: Age must be a positive number!</h2>";
    }
    else {
        // Display formatted data
        echo "<h2>Submitted Information</h2>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        echo "<tr><td>Name</td><td>$name</td></tr>";
        echo "<tr><td>Email</td><td>$email</td></tr>";
        echo "<tr><td>Age</td><td>$age</td></tr>";
        echo "<tr><td>Password</td><td>$password</td></tr>";
        echo "<tr><td>Date of Birth</td><td>$dob</td></tr>";
        echo "<tr><td>Gender</td><td>$gender</td></tr>";
        echo "<tr><td>Favorite Color</td><td>$color</td></tr>";
        echo "</table>";
    }

} else {
    echo "<h2 style='color:red;'>Invalid access!</h2>";
}

?>

</body>
</html>