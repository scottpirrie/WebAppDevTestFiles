<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Template</title>
</head>
<body>
<div>
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

    //Issue
    $sql = "SELECT * FROM `WADphonebook`";
    $result = $conn->query($sql);

    if(!$result){
        die("Query failed");
    }

    //Handle
    echo "<p>".$result->num_rows." rows found</p>";

    //Disconnect
    $conn->close();

    ?>
</div>
</body>
</html>