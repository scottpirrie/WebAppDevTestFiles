<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Samples</title>
</head>
<body>
<p>
    <?php
    $x = 5;
    $y = 2;
    $x = $x/3;
    echo round($x,2);

    echo "<br/>";

    $a = "hello";
    $b = "world";
    $c = "123";
    $d = "321";
    echo $a.$b;
    echo $c+$d;

    echo "<br/>";

    echo strtoupper($a);
    echo "<br/>";
    echo strrev($b);

    echo "<br/>";

    echo "$a $b";
    ?>

</p>
<p>
    <?php
    $price =120;
    if ($price>100) {
        echo "£$price is too much";
    } else {
        echo "Only £$price";
    }
    echo "<br/>";
    echo "--".($price >100)."--";

    echo "<br/>";

    echo "--".(true==false)."--";

    echo "<br/>";

    echo "--".($c==="123")."--";

    ?>
</p>
<hr/>
<p>
    <?php
    $names = array("Mark", "Isla", "Scott", "john");
    print_r($names);
    echo "<br/>";
    array_push($names, "Sandy");
    print_r($names);
    echo "<br/>";

    for ($i = 0; $i<count($names);$i++){
        echo $names[$i]." ";
    }
    echo "<br/>";

    echo "<br/>";

    foreach($names as $name){
        echo $name;
        echo "<br/>";
    }

    echo "<br/>";
    echo "<br/>";

    $phone = array("Mark"=>3497, "John"=>1234, "Scott"=>0305, "Sandy"=>2110);

    foreach($phone as $name=>$phoneNumber){
        echo "$name is on $phoneNumber.";
        echo "<br/>";
    }

    $phone["Fred"]=8888;
    echo "<br/>";
    unset($phone["Sandy"]);

    ksort($phone);
    foreach($phone as $name=>$phoneNumber){
        echo "$name is on $phoneNumber.";
        echo "<br/>";
    }

    echo "<br/>";



    ?>
</p>
</body>
</html>