<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Populate Video Games Table</title>
</head>
<body>
    <h1>Populate Video Games Table</h1>

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

    if ($connection->query("DELETE FROM video_games") === false) {
        die("<p>Error clearing old records: " . htmlspecialchars($connection->error) . "</p>");
    }

    $sql = "INSERT INTO video_games
        (title, genre, platform, release_year, rating, multiplayer)
        VALUES (?, ?, ?, ?, ?, ?)";

    $statement = $connection->prepare($sql);

    if ($statement === false) {
        die("<p>Prepare failed: " . htmlspecialchars($connection->error) . "</p>");
    }

    $games = [
        ["The Legend of Zelda: Breath of the Wild", "Action-adventure", "Nintendo Switch", 2017, 9.7, 0],
        ["Minecraft", "Sandbox", "Multiplatform", 2011, 9.0, 1],
        ["Stardew Valley", "Simulation", "Multiplatform", 2016, 8.9, 1],
        ["Hades", "Roguelike", "Multiplatform", 2020, 9.3, 0],
        ["Mario Kart 8 Deluxe", "Racing", "Nintendo Switch", 2017, 9.2, 1],
        ["Portal 2", "Puzzle", "PC", 2011, 9.5, 1]
    ];

    $insertedCount = 0;

    foreach ($games as $game) {
        $statement->bind_param(
            "sssidi",
            $game[0],
            $game[1],
            $game[2],
            $game[3],
            $game[4],
            $game[5]
        );

        if ($statement->execute()) {
            $insertedCount++;
        } else {
            echo "<p>Error inserting " . htmlspecialchars($game[0]) . ": "
                . htmlspecialchars($statement->error) . "</p>";
        }
    }

    echo "<p>" . $insertedCount . " video game records were inserted successfully.</p>";

    $statement->close();
    $connection->close();
    ?>
</body>
</html>
