<!DOCTYPE html>
<html>
<head>
    <title>Module 3 Programming Assignment</title>
    <meta charset="UTF-8">
</head>
<body>

<h2>Sum Table</h2>

<?php
// Include the external function file
include 'functions.php';
?>

<table border="1">
    <?php
    for ($row = 1; $row <= 10; $row++) {
    ?>
    <tr>
        <?php
        for ($col = 1; $col <= 10; $col++) {

            // Generate two random numbers
            $num1 = rand(1, 50);
            $num2 = rand(1, 50);

            // Call the function
            $sum = getSum($num1, $num2);
        ?>
        <td>
            <?php echo $sum; ?>
        </td>
        <?php
        }
        ?>
    </tr>
    <?php
    }
    ?>
</table>

</body>
</html>