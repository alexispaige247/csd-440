<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alexis Forms</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
            margin: 2rem;
            color: #222222;
        }

        main {
            max-width: 760px;
        }

        h1 {
            color: #23436b;
        }

        form {
            display: grid;
            gap: 1rem;
            margin-top: 1rem;
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

        .message {
            padding: 0.75rem;
            border-left: 4px solid #23436b;
            background-color: #eef4fb;
        }

        .error {
            border-left-color: #a51d2d;
            background-color: #fdecef;
        }

        nav {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <main>
        <nav><a href="AlexisIndex.php">Return to Index</a></nav>
        <h1>Alexis Add Video Game Record</h1>

        <?php
        /*
         * Alexis Mitchell
         * Module 9 Form Page
         * This program validates form input and uses MySQLi prepared
         * statements to add one record to the video_games table.
         */
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        mysqli_report(MYSQLI_REPORT_OFF);

        $title = "";
        $genre = "";
        $platform = "";
        $releaseYear = "";
        $rating = "";
        $multiplayer = "";
        $message = "";
        $messageClass = "message";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = trim($_POST["title"] ?? "");
            $genre = trim($_POST["genre"] ?? "");
            $platform = trim($_POST["platform"] ?? "");
            $releaseYear = trim($_POST["release_year"] ?? "");
            $rating = trim($_POST["rating"] ?? "");
            $multiplayer = $_POST["multiplayer"] ?? "";
            $errors = [];

            if ($title === "") {
                $errors[] = "Title is required.";
            }

            if ($genre === "") {
                $errors[] = "Genre is required.";
            }

            if ($platform === "") {
                $errors[] = "Platform is required.";
            }

            if ($releaseYear === "" || !filter_var($releaseYear, FILTER_VALIDATE_INT)) {
                $errors[] = "Release year must be a whole number.";
            }

            if ($rating === "" || !is_numeric($rating) || $rating < 0 || $rating > 10) {
                $errors[] = "Rating must be a number between 0 and 10.";
            }

            if ($multiplayer !== "0" && $multiplayer !== "1") {
                $errors[] = "Please choose whether the game supports multiplayer.";
            }

            if (count($errors) > 0) {
                $message = implode(" ", $errors);
                $messageClass = "message error";
            } else {
                $host = "localhost";
                $user = "student1";
                $password = "pass";
                $database = "baseball_01";

                $connection = new mysqli($host, $user, $password, $database);

                if ($connection->connect_error) {
                    $message = "Connection failed: " . $connection->connect_error;
                    $messageClass = "message error";
                } else {
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
                        $message = "Table setup failed: " . $connection->error;
                        $messageClass = "message error";
                    } else {
                        $sql = "INSERT INTO video_games
                            (title, genre, platform, release_year, rating, multiplayer)
                            VALUES (?, ?, ?, ?, ?, ?)";

                        $statement = $connection->prepare($sql);

                        if ($statement === false) {
                            $message = "Prepare failed: " . $connection->error;
                            $messageClass = "message error";
                        } else {
                            $yearValue = (int) $releaseYear;
                            $ratingValue = (float) $rating;
                            $multiplayerValue = (int) $multiplayer;

                            $statement->bind_param(
                                "sssidi",
                                $title,
                                $genre,
                                $platform,
                                $yearValue,
                                $ratingValue,
                                $multiplayerValue
                            );

                            if ($statement->execute()) {
                                $message = "The video game record was added successfully.";
                                $title = "";
                                $genre = "";
                                $platform = "";
                                $releaseYear = "";
                                $rating = "";
                                $multiplayer = "";
                            } else {
                                $message = "Insert failed: " . $statement->error;
                                $messageClass = "message error";
                            }

                            $statement->close();
                        }
                    }

                    $connection->close();
                }
            }
        }

        if ($message !== "") {
            echo "<p class=\"" . htmlspecialchars($messageClass) . "\">" . htmlspecialchars($message) . "</p>";
        }
        ?>

        <form method="post" action="AlexisForms.php">
            <div>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" maxlength="100" required value="<?php echo htmlspecialchars($title); ?>">
            </div>

            <div>
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" maxlength="50" required value="<?php echo htmlspecialchars($genre); ?>">
            </div>

            <div>
                <label for="platform">Platform</label>
                <input type="text" id="platform" name="platform" maxlength="50" required value="<?php echo htmlspecialchars($platform); ?>">
            </div>

            <div>
                <label for="release_year">Release Year</label>
                <input type="number" id="release_year" name="release_year" min="1970" max="2100" required value="<?php echo htmlspecialchars($releaseYear); ?>">
            </div>

            <div>
                <label for="rating">Rating</label>
                <input type="number" id="rating" name="rating" min="0" max="10" step="0.1" required value="<?php echo htmlspecialchars($rating); ?>">
            </div>

            <div>
                <label for="multiplayer">Multiplayer</label>
                <select id="multiplayer" name="multiplayer" required>
                    <option value="" <?php echo $multiplayer === "" ? "selected" : ""; ?>>Select One</option>
                    <option value="1" <?php echo $multiplayer === "1" ? "selected" : ""; ?>>Yes</option>
                    <option value="0" <?php echo $multiplayer === "0" ? "selected" : ""; ?>>No</option>
                </select>
            </div>

            <button type="submit">Add Record</button>
        </form>
    </main>
</body>
</html>
