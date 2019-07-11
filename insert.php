<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert in to database</title>
</head>
<body>
<div>
    <h1>Insertion done</h1>

    <?php
    //Connect to database
    $host = "devweb2017.cis.strath.ac.uk";
    $user = "mjb15165";
    $password = "ucaireiv0Pai";
    $dbname = "mjb15165";
    $conn = new mysqli($host,$user,$password,$dbname);

    if ($conn->connect_error){
        die("Connection failed : ".$conn->connect_error); //FIXME remove once working.
    }

    //Setup variables from $_POST
    $name=isset($_POST["name"])?$conn->real_escape_string($_POST["name"]):"";
    $email=isset($_POST["email"])?$conn->real_escape_string($_POST["email"]):"";
    $phone=isset($_POST["phone"])?$conn->real_escape_string($_POST["phone"]):"";
    $twitter=isset($_POST["twitter"])?$conn->real_escape_string($_POST["twitter"]):"";
    $facebook=isset($_POST["facebook"])?$conn->real_escape_string($_POST["facebook"]):"";

    //Check form is valid
    if(empty($name)){
        die("You must provide a name");
    }

    //Create sql query and run it
    $sql = "INSERT INTO `WADphonebook` (`id`, `name`, `email`, `phone`, `twitter`, `facebook`) VALUES 
            (NULL, '$name','$email','$phone','$twitter','$facebook')";

    if($conn->query($sql)===TRUE){
        echo "<p>Insert successful</p>";
    } else {
        die("Something went wrong ".$conn->error); //FIXME
    }


    ?>

</div>
</body>
</html>