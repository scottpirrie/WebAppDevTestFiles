<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mortgage Calculator</title>
</head>
<body>
<h1>
    Mortgage Estimate
</h1>

<?php
$loanAmount = strip_tags(isset($_POST["loanAmount"]) ? (is_numeric($_POST["loanAmount"]) ? $_POST["loanAmount"] : ""): "");
$interestRate = strip_tags(isset($_POST["interestRate"]) ? (is_numeric($_POST["interestRate"]) ? $_POST["interestRate"] : "") : "");
$term = strip_tags(isset($_POST["term"]) ? (is_numeric($_POST["term"]) ? $_POST["term"] : "") : "");
$MAFee = isset($_POST["MAFee"]) ? $_POST["MAFee"] : "";
$MAFeeChecked = isset($_POST["MAFeeChecked"]) && ($_POST["MAFeeChecked"] === "yes");

if (($loanAmount==="") || ($interestRate==="") || ($term==="") || ($MAFeeChecked==true && ($MAFee==="" || $MAFee<0)) || $loanAmount<=0 || $interestRate<=0 || $term<=0) {

    ?>
    <form action="MortCalc.php" method="post">

        <p>
            Loan Amount £<input type="text" name="loanAmount" value="<?php echo $loanAmount; ?>"/> <?php if(($loanAmount==="" || $loanAmount<=0) && $_SERVER["REQUEST_METHOD"] === "POST") echo "Please enter valid loan amount."; ?>
        </p>

        <p>
            Interest Rate <input type="text" name="interestRate" step="0.01" value="<?php echo $interestRate; ?>"/> <?php if(($interestRate==="" || $interestRate<=0) && $_SERVER["REQUEST_METHOD"] === "POST") echo "Please enter valid interest rate."; ?>
        </p>

        <p>
            Term <input type="text" name="term" value="<?php echo $term; ?>"/> (years) <?php if(($term==="" || $term<=0) && $_SERVER["REQUEST_METHOD"] === "POST") echo "Please enter valid term."; ?>
        </p>

        <p>
            Mortgage Arrangement Fee <input type="number" name = "MAFee" value="<?php echo $MAFee; ?>"/> <?php if(($MAFeeChecked==true) && (($MAFee==="") || ($MAFee<=0)) && $_SERVER["REQUEST_METHOD"] === "POST") echo "Please enter valid mortgage arrangement fee."; ?>
        </p>

        <p>
            Yes <input type = 'radio' name ="MAFeeChecked" value= "yes" <?php if($MAFeeChecked) echo "checked"; ?>/>

            No <input type = 'radio' name ="MAFeeChecked" value= "no" <?php if(!$MAFeeChecked) echo "checked"; ?>/>
        </p>

        <p>
            <input type="submit" value="Calculate"/>
        </p>

    </form>
    <?php
    } else {

        if($MAFeeChecked){
            $loanAmount = $loanAmount+$MAFee;
        }

        $j = $interestRate / (12 * 100);
        $n = $term * 12;

        $m = $loanAmount * ($j / (1 - (1 + $j) ** -$n));

        echo "<p>If the interest rate does not change then you will pay £" . round($m) . " per month for a total of $term years</p>";

        ?>
        <table>
            <tr>
                <th>Year</th>
                <th>Balance</th>
            </tr>
            <?php

            for ($i = 0; $i <= $term; $i++) {

                $temp = $loanAmount*(1-((1+$j)**($i*12)-1)/((1+$j)**$n-1));

                ?>

                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo "£".number_format($temp) ?></td>
                </tr>
                <?php

            }

            ?>
            </table>
            <?php

    }

?>
</body>
</html>