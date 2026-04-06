<!DOCTYPE html>
<html>
<head>
    <title>Module 2.2 Programming Assignment</title>
    <meta charset="UTF-8">
</head>
<body>

<h2>Number Table</h2>

<table border="1">
   <?php
   for ($row = 1; $row <= 10; $row++) {
   ?>
    <tr>
        <?php
        for ($col = 1; $col <= 10; $col++) {
        ?>
        <td> 
            <?php 
                echo rand(1, 100); 
            ?> 
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