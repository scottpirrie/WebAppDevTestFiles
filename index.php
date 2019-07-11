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
    <title>Parent View</title>
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
    <h1>Parent Webpage</h1>

    <?php

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

    //Disconnect
    $conn->close();

    ?>
</div>
</body>
</html>