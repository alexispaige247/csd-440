<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alexis Query</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
            margin: 2rem;
            color: #222222;
        }

        main {
            max-width: 1000px;
        }

        h1 {
            color: #23436b;
        }

        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border: 1px solid #cccccc;
            border-radius: 6px;
            background-color: #f8f9fb;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.35rem;
        }

        input,
        select,
        button {
            box-sizing: border-box;
            width: 100%;
            padding: 0.55rem;
            font-size: 1rem;
        }

        button {
            border: 0;
            background-color: #23436b;
            color: #ffffff;
            cursor: pointer;
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid #555555;
            padding: 0.55rem;
            text-align: left;
        }

        th {
            background-color: #eeeeee;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .message {
            padding: 0.75rem;
            border-left: 4px solid #23436b;
            background-color: #eef4fb;
        }

        nav {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <main>
        <nav><a href="AlexisIndex.php">Return to Index</a></nav>
        <h1>Alexis Query Video Games</h1>
        <p>Search for games by title, genre, platform, release year, or multiplayer option.</p>

        <form method="get" action="AlexisQuery.php">
            <div>
                <label for="search">Title Contains</label>
                <input type="text" id="search" name="search" value="<?php echo isset($_GET["search"]) ? htmlspecialchars($_GET["search"]) : ""; ?>">
            </div>

            <div>
                <label for="genre">Genre Contains</label>
                <input type="text" id="genre" name="genre" value="<?php echo isset($_GET["genre"]) ? htmlspecialchars($_GET["genre"]) : ""; ?>">
            </div>

            <div>
                <label for="platform">Platform Contains</label>
                <input type="text" id="platform" name="platform" value="<?php echo isset($_GET["platform"]) ? htmlspecialchars($_GET["platform"]) : ""; ?>">
            </div>

            <div>
                <label for="release_year">Release Year</label>
                <input type="number" id="release_year" name="release_year" min="1970" max="2100" value="<?php echo isset($_GET["release_year"]) ? htmlspecialchars($_GET["release_year"]) : ""; ?>">
            </div>

            <div>
                <label for="multiplayer">Multiplayer</label>
                <select id="multiplayer" name="multiplayer">
                    <option value="" <?php echo (!isset($_GET["multiplayer"]) || $_GET["multiplayer"] === "") ? "selected" : ""; ?>>Any</option>
                    <option value="1" <?php echo (isset($_GET["multiplayer"]) && $_GET["multiplayer"] === "1") ? "selected" : ""; ?>>Yes</option>
                    <option value="0" <?php echo (isset($_GET["multiplayer"]) && $_GET["multiplayer"] === "0") ? "selected" : ""; ?>>No</option>
                </select>
            </div>

            <div class="full-width">
                <button type="submit">Search Records</button>
            </div>
        </form>

        <?php
        /*
         * Alexis Mitchell
         * Module 9 Query Page
         * This program uses MySQLi prepared statements to search records
         * from the Module 8 video_games database table.
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
            die("<p class=\"message\">Connection failed: " . htmlspecialchars($connection->connect_error) . "</p>");
        }

        $createTableSql = "CREATE TABLE IF NOT EXISTS video_games (
            game_id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(100) NOT NULL,
            genre VARCHAR(50) NOT NULL,
            platform VARCHAR(50) NOT NULL,
            release_year INT NOT NULL,
            rating DECIMAL(3,1) NOT NULL,
            multiplayer TINYINT(1) NOT NULL
        )";

        if ($connection->query($createTableSql) === false) {
            die("<p class=\"message\">Table setup failed: " . htmlspecialchars($connection->error) . "</p>");
        }

        $search = trim($_GET["search"] ?? "");
        $genre = trim($_GET["genre"] ?? "");
        $platform = trim($_GET["platform"] ?? "");
        $releaseYear = trim($_GET["release_year"] ?? "");
        $multiplayer = $_GET["multiplayer"] ?? "";

        $searchLike = "%" . $search . "%";
        $genreLike = "%" . $genre . "%";
        $platformLike = "%" . $platform . "%";
        $yearValue = ($releaseYear === "") ? 0 : (int) $releaseYear;
        $multiplayerValue = ($multiplayer === "") ? 0 : (int) $multiplayer;

        $sql = "SELECT game_id, title, genre, platform, release_year, rating, multiplayer
            FROM video_games
            WHERE (? = '' OR title LIKE ?)
                AND (? = '' OR genre LIKE ?)
                AND (? = '' OR platform LIKE ?)
                AND (? = '' OR release_year = ?)
                AND (? = '' OR multiplayer = ?)
            ORDER BY game_id ASC";

        $statement = $connection->prepare($sql);

        if ($statement === false) {
            die("<p class=\"message\">Prepare failed: " . htmlspecialchars($connection->error) . "</p>");
        }

        $statement->bind_param(
            "sssssssisi",
            $search,
            $searchLike,
            $genre,
            $genreLike,
            $platform,
            $platformLike,
            $releaseYear,
            $yearValue,
            $multiplayer,
            $multiplayerValue
        );

        if ($statement->execute() === false) {
            echo "<p class=\"message\">Search failed: " . htmlspecialchars($statement->error) . "</p>";
        } else {
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
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
                echo "<p class=\"message\">No matching video game records were found.</p>";
            }
        }

        $statement->close();
        $connection->close();
        ?>
    </main>
</body>
</html>
