<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Drop Video Games Table</title>
</head>
<body>
    <h1>Drop Video Games Table</h1>

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

    $sql = "DROP TABLE IF EXISTS video_games";

    if ($connection->query($sql) === true) {
        echo "<p>The video_games table was dropped successfully.</p>";
    } else {
        echo "<p>Error dropping table: " . htmlspecialchars($connection->error) . "</p>";
    }

    $connection->close();
    ?>
</body>
</html>
