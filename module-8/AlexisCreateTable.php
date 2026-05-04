<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Video Games Table</title>
</head>
<body>
    <h1>Create Video Games Table</h1>

    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    mysqli_report(MYSQLI_REPORT_OFF);

    $host = "localhost";
    $user = "student1";
    $password = "pass";
    $database = "baseball_01";

    $connection = new mysqli($host, $user, $password, $database);

    if ($connection->connect_error) {
        die("<p>Connection failed: " . htmlspecialchars($connection->connect_error) . "</p>");
    }

    $sql = "CREATE TABLE IF NOT EXISTS video_games (
        game_id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        genre VARCHAR(50) NOT NULL,
        platform VARCHAR(50) NOT NULL,
        release_year INT NOT NULL,
        rating DECIMAL(3,1) NOT NULL,
        multiplayer TINYINT(1) NOT NULL
    )";

    if ($connection->query($sql) === true) {
        echo "<p>The video_games table was created successfully.</p>";
    } else {
        echo "<p>Error creating table: " . htmlspecialchars($connection->error) . "</p>";
    }

    $connection->close();
    ?>
</body>
</html>
