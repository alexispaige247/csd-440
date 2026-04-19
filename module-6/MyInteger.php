<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AlexisMyInteger</title>
</head>
<body>
<?php
class AlexisMyInteger
{    
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {        
        $this->value = $value;
    }

    public function isEven($number)
    {
        return $number % 2 == 0;
    }

    public function isOdd($number)
    {
        return $number % 2 != 0;
    }

    public function isPrime()
    {
        if ($this->value <= 1) {
            return false;
        }
        for ($i = 2; $i <= sqrt($this->value); $i++) {
            if ($this->value % $i == 0) {
                return false;
            }
        }
        return true;
    }
}

$firstNumber = new AlexisMyInteger(7);
$secondNumber = new AlexisMyInteger(12);

$secondNumber->setValue(15);
?>

<h1>My Integers</h1>
<h2>First Instance</h2>
    <p>Stored value: <?php echo $firstNumber->getValue(); ?></p>
    <p>isEven(<?php echo $firstNumber->getValue(); ?>): <?php echo $firstNumber->isEven($firstNumber->getValue()) ? 'true' : 'false'; ?></p>
    <p>isOdd(<?php echo $firstNumber->getValue(); ?>): <?php echo $firstNumber->isOdd($firstNumber->getValue()) ? 'true' : 'false'; ?></p>
    <p>isPrime(): <?php echo $firstNumber->isPrime() ? 'true' : 'false'; ?></p>

    <h2>Second Instance</h2>
    <p>Original constructor value: 12</p>
    <p>Updated value after setter: <?php echo $secondNumber->getValue(); ?></p>
    <p>isEven(<?php echo $secondNumber->getValue(); ?>): <?php echo $secondNumber->isEven($secondNumber->getValue()) ? 'true' : 'false'; ?></p>
    <p>isOdd(<?php echo $secondNumber->getValue(); ?>): <?php echo $secondNumber->isOdd($secondNumber->getValue()) ? 'true' : 'false'; ?></p>
    <p>isPrime(): <?php echo $secondNumber->isPrime() ? 'true' : 'false'; ?></p>
</body>
</html>