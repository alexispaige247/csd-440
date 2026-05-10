<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alexis Query Video Games Table</title>
    <style>
        table {
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid #555;
            padding: 0.5rem;
            text-align: left;
        }

        th {
            background-color: #eeeeee;
        }
    </style>
</head>
<body>
    <h1>Alexis Query Video Games Table</h1>

    <?php
    /*
     * Alexis Mitchell
     * Module 8 Query Table File
     * This program displays all records from the video_games table.
     */
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

    $sql = "SELECT game_id, title, genre, platform, release_year, rating, multiplayer
        FROM video_games
        ORDER BY rating DESC, title ASC";

    $result = $connection->query($sql);

    if ($result === false) {
        echo "<p>Error querying table: " . htmlspecialchars($connection->error) . "</p>";
    } elseif ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Title</th>";
        echo "<th>Genre</th>";
        echo "<th>Platform</th>";
        echo "<th>Release Year</th>";
        echo "<th>Rating</th>";
        echo "<th>Multiplayer</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["game_id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["genre"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["platform"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["release_year"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["rating"]) . "</td>";
            echo "<td>" . ($row["multiplayer"] ? "Yes" : "No") . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No video game records were found.</p>";
    }

    $connection->close();
    ?>
</body>
</html>
