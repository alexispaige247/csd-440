<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>Palindrome Program</title>
</head>
<body>
    <h1>Palindrome Program</h1>
<?php
// Function to check if the string is palindrome or not
function Palindrome($string){  
    if (strrev($string) == $string){  
        return 1;  
    }
    else{
        return 0;
    }
}  

// 6 examples
$words = array("DAD","MOM","RACECAR","HELLO","HOUSE","STORE");

foreach($words as $original) {
    if(Palindrome($original)){  
        echo "$original is Palindrome<br>";  
    } 
    else {  
        echo "$original is not a Palindrome<br>";  
    }
}
?>

</body>
</html>