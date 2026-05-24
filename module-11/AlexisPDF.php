<?php

error_reporting(E_ALL);
ini_set("display_errors", 0);
ini_set("log_errors", 1);
mysqli_report(MYSQLI_REPORT_OFF);

/**
 * Escapes text so it can be safely written inside a PDF text object.
 * @param string $text Text to escape.
 * @return string Escaped PDF text.
 */
function pdfEscape($text)
{
    return str_replace(
        ["\\", "(", ")", "\r", "\n"],
        ["\\\\", "\\(", "\\)", " ", " "],
        (string) $text
    );
}

/**
 * Keeps text within a reasonable width for the PDF table cells.
 * @param string $text Text to shorten.
 * @param int $maximumCharacters Maximum number of characters to display.
 * @return string Shortened text.
 */
function shortenText($text, $maximumCharacters)
{
    $text = (string) $text;

    if (strlen($text) <= $maximumCharacters) {
        return $text;
    }

    return substr($text, 0, $maximumCharacters - 3) . "...";
}

/**
 * Adds a text command to the PDF page stream.
 * @param array $commands Existing PDF page commands.
 * @param float $x Horizontal position.
 * @param float $y Vertical position.
 * @param string $text Text to display.
 * @param int $fontSize Font size.
 * @return void
 */
function addPdfText(&$commands, $x, $y, $text, $fontSize = 10)
{
    $commands[] = "BT /F1 " . $fontSize . " Tf " . $x . " " . $y . " Td (" . pdfEscape($text) . ") Tj ET";
}

/**
 * Adds a rectangle command to the PDF page stream.
 * @param array $commands 
 * @param float $x 
 * @param float $y 
 * @param float $width 
 * @param float $height 
 * @return void
 */
function addPdfRectangle(&$commands, $x, $y, $width, $height)
{
    $commands[] = $x . " " . $y . " " . $width . " " . $height . " re S";
}

/**
 * Builds a simple one-page PDF from a list of PDF drawing commands.
 * @param array $commands PDF page drawing commands.
 * @return string Complete PDF file content.
 */
function buildPdf($commands)
{
    $stream = implode("\n", $commands);
    $objects = [
        "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n",
        "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n",
        "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n",
        "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n",
        "5 0 obj\n<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream\nendobj\n"
    ];

    $pdf = "%PDF-1.4\n";
    $offsets = [0];

    foreach ($objects as $object) {
        $offsets[] = strlen($pdf);
        $pdf .= $object;
    }

    $xrefPosition = strlen($pdf);
    $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
    $pdf .= "0000000000 65535 f \n";

    for ($index = 1; $index <= count($objects); $index++) {
        $pdf .= sprintf("%010d 00000 n \n", $offsets[$index]);
    }

    $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
    $pdf .= "startxref\n" . $xrefPosition . "\n%%EOF";

    return $pdf;
}

/**
 * Retrieves video game records from the Module 8 database table.
 * @return array Report data and status details.
 */
function fetchVideoGameData()
{
    $host = "localhost";
    $user = "student1";
    $password = "pass";
    $database = "baseball_01";

    $connection = @new mysqli($host, $user, $password, $database);

    if ($connection->connect_error) {
        return [
            "success" => false,
            "message" => "Connection failed: " . $connection->connect_error,
            "records" => []
        ];
    }

    $sql = "SELECT game_id, title, genre, platform, release_year, rating, multiplayer
        FROM video_games
        ORDER BY rating DESC, title ASC";
    $result = $connection->query($sql);

    if ($result === false) {
        $message = "Error querying table: " . $connection->error;
        $connection->close();

        return [
            "success" => false,
            "message" => $message,
            "records" => []
        ];
    }

    $records = [];

    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }

    $connection->close();

    return [
        "success" => true,
        "message" => "Video game records loaded successfully.",
        "records" => $records
    ];
}

$reportData = fetchVideoGameData();
$records = $reportData["records"];
$commands = [];

// Page border and report title.
addPdfRectangle($commands, 24, 24, 564, 744);
addPdfText($commands, 50, 735, "Module 11 PDF Report: Video Game Data", 18);
addPdfText($commands, 50, 713, "By Alexis Mitchell", 11);

// Topic overview section.
addPdfText($commands, 50, 680, "General Topic Information", 14);
addPdfText($commands, 50, 660, "Video games are interactive digital experiences created for entertainment, competition,", 10);
addPdfText($commands, 50, 646, "storytelling, problem solving, and social play. Game data can be organized by title,", 10);
addPdfText($commands, 50, 632, "genre, platform, release year, rating, and whether the game supports multiplayer.", 10);
addPdfText($commands, 50, 610, "This report uses the Module 8 video_games table", 10);

if ($reportData["success"] === false) {
    addPdfText($commands, 50, 560, "Database Status", 14);
    addPdfText($commands, 50, 540, $reportData["message"], 10);
} else {
    $tableX = 36;
    $tableY = 555;
    $rowHeight = 24;
    $columnWidths = [30, 170, 80, 95, 65, 50, 50];
    $headers = ["ID", "Title", "Genre", "Platform", "Year", "Rating", "Multi"];
    $footerY = $tableY - ($rowHeight * (count($records) + 1));

    // Header row for the data table.
    addPdfText($commands, 36, 580, "Video Game Database Records", 14);
    $currentX = $tableX;

    foreach ($headers as $index => $header) {
        addPdfRectangle($commands, $currentX, $tableY, $columnWidths[$index], $rowHeight);
        addPdfText($commands, $currentX + 4, $tableY + 8, $header, 9);
        $currentX += $columnWidths[$index];
    }

    // Database rows.
    foreach ($records as $recordIndex => $record) {
        $currentX = $tableX;
        $currentY = $tableY - ($rowHeight * ($recordIndex + 1));
        $rowValues = [
            $record["game_id"],
            shortenText($record["title"], 29),
            shortenText($record["genre"], 13),
            shortenText($record["platform"], 16),
            $record["release_year"],
            number_format((float) $record["rating"], 1),
            $record["multiplayer"] ? "Yes" : "No"
        ];

        foreach ($rowValues as $index => $value) {
            addPdfRectangle($commands, $currentX, $currentY, $columnWidths[$index], $rowHeight);
            addPdfText($commands, $currentX + 4, $currentY + 8, $value, 8);
            $currentX += $columnWidths[$index];
        }
    }

    // Footer row for the data table.
    addPdfRectangle($commands, $tableX, $footerY, 540, $rowHeight);
    addPdfText($commands, $tableX + 4, $footerY + 8, count($records) . " video game record(s) displayed.", 9);
}

// Page footer.
addPdfText($commands, 50, 45, "Generated from Module 8 database data for Module 11.", 9);
addPdfText($commands, 450, 45, "Page 1", 9);

$pdf = buildPdf($commands);

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=\"Alexis Video Games Report.pdf\"");
header("Content-Length: " . strlen($pdf));

echo $pdf;
?>
