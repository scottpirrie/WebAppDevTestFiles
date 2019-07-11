<?php
    //Connect
    $host = "devweb2017.cis.strath.ac.uk";
    $user = "mjb15165";
    $password = "ucaireiv0Pai";
    $dbname = "mjb15165";
    $conn = new mysqli($host,$user,$password,$dbname);

    if ($conn->connect_error){
        die("Connection failed : ".$conn->connect_error); //FIXME remove once working.
    }

    //Get value from form
    $userID = $conn->real_escape_string($_GET["id"]);

    //Issue
    $sql = "SELECT * FROM `WADphonebook` WHERE `id` = $userID";
    $result = $conn->query($sql);

    //Handle
    if(!$result){
        die("Query failed");
    }

    if($result->num_rows>0){
        while ($row = $result->fetch_assoc()){
        $lastMatchingRow = "<p>".$row["id"]." ".$row["name"]." ".$row["email"]." ".$row["phone"]." ".$row["twitter"]." ".$row["facebook"]."</p>";
        }
    }

    //Disconnect
    $conn->close();
    ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Show contact</title>
</head>
<body>
<div>
    <h1>
        Show contact details
    </h1>
    <?php
    echo $lastMatchingRow;
    ?>


</div>
</body>
</html>