<?php

$con = mysql_connect("localhost", "webairco", "Ipodlover666");
if (!$con) {
    die(mysql_error());
}
mysql_select_db("webairco_main", $con) or die(mysql_error());

$q = "delete from flight_alloc where flight_date<CURDATE()";
if (mysql_query($q, $con)) {
    echo "success";
}
else
    echo "fail" . mysql_error ();

for ($i = 1; $i < 12; $i++) {
    $q = "insert into flight_alloc values ($i, DATE_ADD(CURDATE(), INTERVAL 3 MONTH), 0)";
    if (mysql_query($q, $con)) {
        echo "success";
    }
    else
        echo "fail" . mysql_error ();
}

mysql_close($con);
?>
