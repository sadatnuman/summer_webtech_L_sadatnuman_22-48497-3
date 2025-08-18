<!DOCTYPE html>
<html>
<head>
    <title>PHP Tasks</title>
</head>
<body>

<h1>PHP Tasks (Without User Input)</h1>

<?php
// Task 1: Sum of Array
echo "<h2>1. Find Sum of Array</h2>";
$arr1 = [10, 20, 30, 40, 50];
$sum = array_sum($arr1);
echo "Array: " . implode(", ", $arr1) . "<br>";
echo "Sum = " . $sum . "<br><br>";

// Task 2: Second Maximum
echo "<h2>2. Find Second Maximum</h2>";
$arr2 = [5, 15, 25, 35, 45];
rsort($arr2);
if (count($arr2) >= 2) {
    echo "Array: " . implode(", ", $arr2) . "<br>";
    echo "Second Maximum = " . $arr2[1] . "<br><br>";
} else {
    echo "Need at least two numbers.<br><br>";
}

// Task 3: Right-Angled Star Triangle
echo "<h2>3. Right-Angled Star Triangle</h2>";
$rows = 5;
for ($i = 1; $i <= $rows; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo "*";
    }
    echo "<br>";
}
echo "<br>";

// Task 4: Reverse a String
echo "<h2>4. Reverse a String</h2>";
$str = "HelloWorld";
$rev = strrev($str);
echo "Original String = " . $str . "<br>";
echo "Reversed String = " . $rev . "<br><br>";

// Task 5: Separate Vowels and Consonants
echo "<h2>5. Separate Vowels and Consonants</h2>";
$word = "Bangladesh";
$vowels = "";
$consonants = "";
for ($i = 0; $i < strlen($word); $i++) {
    $ch = strtolower($word[$i]);
    if (in_array($ch, ['a','e','i','o','u'])) {
        $vowels .= $word[$i] . " ";
    } elseif (ctype_alpha($ch)) {
        $consonants .= $word[$i] . " ";
    }
}
echo "Word = " . $word . "<br>";
echo "Vowels: " . $vowels . "<br>";
echo "Consonants: " . $consonants . "<br>";
?>

</body>
</html>
