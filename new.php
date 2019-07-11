<?php

function safePOST($conn,$name){
    if(isset($_POST[$name])){
        return $conn->real_escape_string(strip_tags($_POST[$name]));
    } else {
        return "";
    }
}

//Connect
$host = "devweb2017.cis.strath.ac.uk";
$user = "mjb15165";
$password = "ucaireiv0Pai";
$dbname = "mjb15165";
$conn = new mysqli($host,$user,$password,$dbname);

if ($conn->connect_error){
    die("Connection failed : ".$conn->connect_error); //FIXME remove once working.
}

$action = isset($_POST["action"])?$_POST["action"]:"";
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Tournament</title>
</head>
<style>
    h2 {
        background-color: #4CAF50;
        color: white;
    }

    tr:nth-child(even) {background-color: #f2f2f2}

    table, th, td {
        border: 1px solid black;
    }
    h1{
        background-color: palevioletred;
    }

    body{
        background-color: antiquewhite;
    }
</style>
<body>
<div>
    <h1>Update database</h1>

    <h3>
        Go to current tournament <a href="https://devweb2017.cis.strath.ac.uk/~mjb15165/wad_test/coach.php">here</a>
        </br>
        View parent view <a href="https://devweb2017.cis.strath.ac.uk/~mjb15165/wad_test/index.php">here</a>
    </h3>

    <?php
    if($action=="getform") {
        //*******************DISPLAY FORM FOR PLAYER NAMES*******************
        $numOfPlayers = $_POST["numOfPlayers"];

        echo "<form method='post'>";
        for ($i = 0; $i < $numOfPlayers; $i++) {
            echo "Enter name of Player " . ($i + 1) . "<input type=\"text\" name=\"names[]\"><br/>";
        }
        echo "<input type=\"hidden\" name=\"action\" value=\"setscores\">";
        echo "<input type=\"submit\" class=\"button\">";
        echo "</form>";

    } else if($action=="setscores"){

        //TODO setscores

        $delete="TRUNCATE TABLE tennisplayers";
        $conn->query($delete);

        $names=$_POST["names"];
        //$names=array();
        //array_push($names,safePOST($conn,"names"));
        for($i=0;$i<count($names);$i++){
            $names[$i]=mysqli_real_escape_string($conn,strip_tags($names[$i]));
            if ($names[$i] != "") {
                $sql = "INSERT INTO `tennisplayers` (`name`,`wins`) VALUES ('$names[$i]',0)";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Inserted " . $names[$i] . " successfully</p>";
                } else {
                    die("Something went wrong " . $conn->error); //FIXME
                }
            }
        }

        //Truncate table
        $sql = "TRUNCATE TABLE tennismatches";
        $conn->query($sql);

        //Create all possible matches
        for($x=1;$x<=count($names);$x++){
            for($y=$x+1;$y<=count($names);$y++){
                $sql = "INSERT INTO `tennismatches` (`matchid`, `player1id`, `player2id`, `player1score`, `player2score`) VALUES (NULL, '$x', '$y', 0, 0)";
                $conn->query($sql);
            }
        }

        $sql = "UPDATE tennisplayers SET wins = 0";
        $conn->query($sql);

        $sql="SELECT * FROM tennismatches";
        $result =$conn->query($sql);
        if(!$result){
            die("Query failed".$conn->error);
        }

        echo "<form action=\"coach.php\" method=\"post\">";
        echo "<button type=\"submit\">Proceed to tournament page</button>";
        echo "</form>";



    } else {
        //*******************ASK USER HOW MANY PLAYERS*******************

        echo "<form method=\"post\">";
        echo "Enter number of players <input type=\"number\" name=\"numOfPlayers\">";

        echo "<input type=\"hidden\" name=\"action\" value=\"getform\">";
        echo "<input type=\"submit\" class=\"button\">";
        echo "</form>";
    }

    //Disconnect
    $conn->close();

    ?>
</div>
</body>
</html>