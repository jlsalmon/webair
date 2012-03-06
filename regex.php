<?php include('include/session.php'); ?>
<html>
    <body>
        <form action="regex.php" method="post">
            <input type="text" name="date" maxlength="10" />
            <input type="hidden" value="subtest"/>
            <input type="submit" value="Check" />
        </form>
        <?php
        $subdate = $_POST['date'];

        $regex = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
        if (preg_match($regex, $subdate)) {
            list( $year , $month , $day ) = explode('-',$subdate);
            if(checkdate( $month , $day , $year )) {
                echo "Pass";
            }
            else echo "Fail :(";
        }
        else {
            echo "Fail :(";
        }
        ?>
    </body>
</html>