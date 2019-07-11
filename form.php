<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My first integrated form</title>
</head>
<body>
<h1>
    My first form
</h1>
<?php
$forename = strip_tags(isset($_POST["forename"]) ? $_POST["forename"] : "");
$surname = strip_tags(isset($_POST["surname"]) ? $_POST["surname"] : "");
$isHappy = isset($_POST["happy"]) && ($_POST["happy"]==="yes");


if (($forename==="") || ($surname==="")) {
    if ($_SERVER["REQUEST_METHOD"]==="POST"){
        echo "<p>Please complete all fields</p>";
    }
    ?>
    <form action="form.php" method="post">
        <p>
            What is your name?
            <input type="text" name = "forename" value="<?php echo $forename; ?>"/>
            <input type="text" name = "surname" value="<?php echo $surname; ?>"/>
        </p>

        <p>
            <input type="checkbox" name="happy" value="yes" <?php if($isHappy) echo "checked"; ?>> I am happy
        </p>

        <p>
            <input type = "submit"/>
        </p>
    </form>
    <?php
} else{
    echo "<p>Your name is $forename $surname.</p>";
        if ($isHappy) {
            echo "<p>Glad to hear you're happy</p>";
        }
    }

?>

</body>
</html>