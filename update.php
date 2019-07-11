
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

    //safely get all parameters
    $name = safePOST($conn,"name");
    $email = safePOST($conn,"email");
    $phone = safePOST($conn,"phone");
    $twitter = safePOST($conn,"twitter");
    $facebook = safePOST($conn,"facebook");
    $id = safePOST($conn,"id");
    $action = safePost($conn,"action");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update</title>
</head>
<body>
<div>
    <h1>Updated database</h1>

    <?php
    if($action=="doupdate") {
        //*******************DO FINAL UPDATE TO THE DATABASE*******************
        if(($name=="")&&($email=="")&&($phone=="")&&($twitter=="")&&($facebook=="")){
            die("No values set");
        }
        $sql = "UPDATE `WADphonebook` SET `name` = '$name', `email` = '$email', `phone` = '$phone', `twitter` = '$twitter', `facebook` = '$facebook' 
            WHERE `WADphonebook`.`id` = $id";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Insert successful</p>";
        } else {
            die("Something went wrong " . $conn->error); //FIXME
        }
    } else if($action=="getform"){
        //*******************GET POPULATED FORM FOR ID==$ID*******************
        $sql = "SELECT * FROM `WADphonebook` WHERE `id` = $id";//FIXME make $id
        $result = $conn->query($sql);

        //Handle
        if(!$result){
            die("Query failed");
        }



        if($result->num_rows>0){
            if ($row = $result->fetch_assoc()){
                $name = $row["name"];
                $email = $row["email"];
                $phone = $row["phone"];
                $twitter = $row["twitter"];
                $facebook = $row["facebook"];
            }
        }
        ?>
        <form method="post">
            <p>
                Name: <input type="text" name="name" value="<?php echo $name;?>"/><br/>
                Email: <input type="text" name="email" value="<?php echo $email;?>"/><br/>
                Phone: <input type="text" name="phone" value="<?php echo $phone;?>"/><br/>
                Twitter: <input type="text" name="twitter" value="<?php echo $twitter;?>"/><br/>
                Facebook: <input type="text" name="facebook" value="<?php echo $facebook;?>"/><br/>
                <input type="hidden" name="action" value="doupdate">
                <input type="hidden" name="id" value="<?php echo $id;?>">
                <input type="submit"/>
            </p>
        </form>
        <?php
    } else {
        //*******************ASK USER TO PICK AN ID*******************
        $sql = "SELECT * FROM `WADphonebook`";
        $result = $conn->query($sql);

        if(!$result){
            die("Query failed");
        }
        echo " <form method=\"post\"><select name=\"id\">";

        if($result->num_rows>0){
            while ($row = $result->fetch_assoc()){
                echo "<option value=\"".$row["id"]."\">".$row["name"]."</option>\n";
            }
        }
        echo "</select>";
        echo "<input type=\"hidden\" name=\"action\" value=\"getform\">";
        echo "<input type=\"submit\"/>";
        echo "</form>";
    }

    //Disconnect
    $conn->close();

    ?>
</div>
</body>
</html>
