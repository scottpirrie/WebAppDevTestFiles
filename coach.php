<?php

function safePOST($conn,$name){
    if(isset($_POST[$name])){
        return $conn->real_escape_string(strip_tags($_POST[$name]));
    } else {
        return "";
    }
}

function getName($conn,$id){
    $sql="SELECT * from tennisplayers";
    $result=$conn->query($sql);
    if(!$result){
        die("Query failed");
    }
    if($result->num_rows>0){
        while ($row = $result->fetch_assoc()){
            if($id==$row["id"]){
                return $row["name"];
            }

        }
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

$action = safePost($conn,"action");

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Tournament Page</title>
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
    .button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 2px 8px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 12px;
    }
</style>
<body>
<div>
    <h1>Coach Webpage</h1>

    <h3>
        Start a new tournament <a href="https://devweb2017.cis.strath.ac.uk/~mjb15165/wad_test/new.php">here</a>
    </br>
        View parent view <a href="https://devweb2017.cis.strath.ac.uk/~mjb15165/wad_test/index.php">here</a>
    </h3>

    <?php

    if($action=="displaytables") {
        $score1 = $_POST["score1"];
        $score2 = $_POST["score2"];
        $id1 = $_POST["id1"];
        $id2 = $_POST["id2"];

        $matchPlayed = false;

        $sql="SELECT * FROM tennismatches";
        $result =$conn->query($sql);
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()) {
                if ($row["player1id"] == $id1 && $row["player2id"] == $id2 && ($row["player1score"] != 0 || $row["player2score"] != 0)){
                    $matchPlayed=true;
                } else if($row["player1id"] == $id2 && $row["player2id"] == $id1 && ($row["player1score"] != 0 || $row["player2score"] != 0)){
                    $matchPlayed=true;
                }
            }
        }

        $noScore=false;
        if($score1=="" || $score2==""){
            $noScore=true;
        }

        if($matchPlayed){
            echo "<p style=\"color:red;\">Match has already been played</p>";
        } elseif($noScore) {
            echo "<p style=\"color:red;\">No score was entered</p>";
        }else{

            if ($score1 == $score2 || $id1 == $id2) {
                if($score1==$score2) {
                    echo "<p style=\"color:red;\"> Match was a draw and was not recorded</p>";
                } elseif($id1 == $id2){
                    echo "<p style=\"color:red;\">Players cannot play against themselves</p>";
                }
            } else {

                if ($score1 > $score2) {
                    $winner = $id1;
                } else {
                    $winner = $id2;
                }
                //------------------------------------------------------------------------------

                $wins = 0;

                $sql = "SELECT * FROM tennisplayers";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Broke" . $conn->error);
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if ($row["id"] == $winner) {
                            $wins = $row["wins"];
                        }
                    }
                }

                $wins = $wins + 1;


                $sql = "UPDATE `tennisplayers` SET `wins`=$wins WHERE `id`=$winner";
                $result = $conn->query($sql);
                if (!$result) {
                    die("Query didnt work" . $conn->error);
                }


                //------------------------------------------------------------------------------

                $sql = "UPDATE `tennismatches` SET `player1score`=$score1,`player2score`=$score2 WHERE `player1id`=$id1 AND `player2id`=$id2";
                $result = $conn->query($sql);

                $sql = "UPDATE `tennismatches` SET `player1score`=$score2,`player2score`=$score1 WHERE `player1id`=$id2 AND `player2id`=$id1";
                $result = $conn->query($sql);
            }
        }

        $sql="SELECT * FROM tennismatches";
        $result =$conn->query($sql);
        if(!$result){
            die("Query failed".$conn->error);
        }
        echo "<h2>Played matches</h2>";
        echo "<table>";
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()) {
                if ($row["player1score"] != 0 || $row["player2score"] != 0){
                    echo "<tr>";
                    echo "<td>" . getName($conn, $row["player1id"]) . "</td>";
                    echo "<td>" . $row["player1score"] . "</td>";
                    echo "<td> V </td>";
                    echo "<td>" . $row["player2score"] . "</td>";
                    echo "<td>" . getName($conn, $row["player2id"]) . "</td>";
                    echo "</tr>";
                }
            }
        }
        echo "</table>";

        echo "<table>";
        $sql="SELECT * FROM tennismatches";
        $result =$conn->query($sql);
        echo "<h2>Unplayed matches</h2>";
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()){
                if($row["player1score"]==0 && $row["player2score"]==0) {
                    echo "<tr>";
                    echo "<td>" . getName($conn, $row["player1id"]) . "</td>";
                    echo "<td> V </td>";
                    echo "<td>" . getName($conn, $row["player2id"]) . "</td>";
                    echo "</tr>";
                }
            }
        }
        echo "</table>";

        echo "<h2>Number of wins</h2>";

        $sql = "SELECT * FROM `tennisplayers` ORDER BY `wins` DESC";
        $result = $conn->query($sql);
        if($result->num_rows>0){
            echo "<table>";
            while($row=$result->fetch_assoc()){
                echo "<tr>";
                echo "<td>".$row["name"];
                echo "<td>".$row["wins"];
                echo "</tr>";
            }
            echo "</table>";
        }

        echo"<h2>Enter scores of a match that has been played</h2>";
        $sql = "SELECT * FROM `tennisplayers`";
        $result = $conn->query($sql);

        echo"<form method=\"post\">";

        $sql = "SELECT * FROM `tennisplayers`";
        $result = $conn->query($sql);
        echo "<select name=\"id1\">";
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()){
                echo "<option value=\"".$row["id"]."\">".$row["name"]."</option>\n";
            }
        }
        echo "</select>";

        echo "<input type=\"number\" name=\"score1\">";
        echo "<input type=\"number\" name=\"score2\">";

        $sql = "SELECT * FROM `tennisplayers`";
        $result = $conn->query($sql);
        echo "<select name=\"id2\">";
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()){
                echo "<option value=\"".$row["id"]."\">".$row["name"]."</option>\n";
            }
        }
        echo "</select>";
        echo "<input type=\"hidden\" name=\"action\" value=\"displaytables\">";
        echo "<input type=\"submit\" class=\"button\">";


        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()){
                echo "<option value=\"".$row["id"]."\">".$row["name"]."</option>\n";
            }
        }
        echo "</select>";
        echo "</form>";
    } else {
        //Get all names in $names
        $sql = "SELECT * FROM `tennismatches`";
        $result = $conn->query($sql);

        if(!$result){
            die("Query failed");
        }

        echo "<h2>Played matches</h2>";
        echo "<table>";
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()){
                if($row["player1score"]!=0 || $row["player2score"]!=0) {
                    echo "<tr>";
                    echo "<td>" . getName($conn, $row["player1id"]) . "</td>";
                    echo "<td>" . $row["player1score"] . "</td>";
                    echo "<td> V </td>";
                    echo "<td>" . $row["player2score"] . "</td>";
                    echo "<td>" . getName($conn, $row["player2id"]) . "</td>";
                    echo "</tr>";
                }
            }
        }
        echo "</table>";

        echo "<table>";
        $sql="SELECT * FROM tennismatches";
        $result =$conn->query($sql);
        echo "<h2>Unplayed matches</h2>";
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()){
                if($row["player1score"]==0 && $row["player2score"]==0) {
                    echo "<tr>";
                    echo "<td>" . getName($conn, $row["player1id"]) . "</td>";
                    echo "<td> V </td>";
                    echo "<td>" . getName($conn, $row["player2id"]) . "</td>";
                    echo "</tr>";
                }
            }
        }
        echo     "</table>";

        echo "<h2>Number of wins</h2>";

        $sql = "SELECT * FROM `tennisplayers` ORDER BY `wins` DESC";
        $result = $conn->query($sql);
        if($result->num_rows>0){
            echo "<table>";
            while($row=$result->fetch_assoc()){
                echo "<tr>";
                echo "<td>".$row["name"];
                echo "<td>".$row["wins"];
                echo "</tr>";
            }
            echo "</table>";
        }


        echo"<h2>Enter scores of a match that has been played</h2>";
        $sql = "SELECT * FROM `tennisplayers`";
        $result = $conn->query($sql);



        echo"<form method=\"post\">";

        echo "<select name=\"id1\">";
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()){
                echo "<option value=\"".$row["id"]."\">".$row["name"]."</option>\n";
            }
        }
        echo "</select>";

        echo "<input type=\"number\" name=\"score1\">";
        echo "<input type=\"number\" name=\"score2\">";

        $sql = "SELECT * FROM `tennisplayers`";
        $result = $conn->query($sql);
        echo "<select name=\"id2\">";
        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()){
                echo "<option value=\"".$row["id"]."\">".$row["name"]."</option>\n";
            }
        }
        echo "</select>";
        echo "<input type=\"hidden\" name=\"action\" value=\"displaytables\">";
        echo "<input type=\"submit\" class=\"button\">";


        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()){
                echo "<option value=\"".$row["id"]."\">".$row["name"]."</option>\n";
            }
        }
        echo "</select>";
        echo "</form>";

    }


    //Disconnect
    $conn->close();

    ?>
</div>
</body>
</html>