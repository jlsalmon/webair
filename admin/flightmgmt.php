<?
include("../include/session.php");

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function displayTodaysBookings() {
    global $database;
    $date = date('Y-m-d');
    $q = "select flight_info.flightid, flight_info.departure_time, "
            . "flight_info.arrival_time, flight_info.origin, "
            . "flight_info.destination, flight_alloc.seats_alloc, flight_alloc.flight_date "
            . "from flight_info, flight_alloc "
            . "where flight_info.flightid = flight_alloc.flight_id "
            . "and flight_alloc.flight_date = '$date'";
    $result = $database->query($q);
    /* Error occurred, return given name by default */
    $num_rows = mysql_numrows($result);
    if (!$result || ($num_rows < 0)) {
        echo "Error displaying info";
        return;
    }
    if ($num_rows == 0) {
        echo "Database table empty";
        return;
    }
    /* Display table contents */
    echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "<tr><th>Flight ID</th><th>Departure Date</th><th>Origin</th><th>Destination</th><th>Departure Time</th><th>Arrival Time</th><th>Num. Seats Allocated</th></tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
        $flight_id = mysql_result($result, $i, "flightid");
        $date = mysql_result($result, $i, "flight_date");
        $origin = mysql_result($result, $i, "origin");
        $destination = mysql_result($result, $i, "destination");
        $deptime = mysql_result($result, $i, "departure_time");
        $arrtime = mysql_result($result, $i, "arrival_time");
        $alloc = mysql_result($result, $i, "seats_alloc");

        echo "<tr><td>$flight_id</td><td>$date</td><td>$origin</td><td>$destination</td><td>$deptime</td><td>$arrtime</td><td>$alloc</td></tr>\n";
    }
    echo "</table>\n";
}

function printCustDetails() {
    global $database;
    $result = $database->getCustDetails($_SESSION['custflightdate'], $_SESSION['custflightid']);

    $num_rows = mysql_numrows($result);
    if (!$result || ($num_rows < 0)) {
        echo "Error displaying info";
        return;
    }
    if ($num_rows == 0) {
        echo "Database table empty";
        return;
    }
    /* Display table contents */
    echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "<tr>
        <th>Username</th><th>First Name</th><th>Last Name</th><th>Booking Ref</th>
        <th>Flight I.D</th><th>Departure Date</th><th>Total Price</th>
        <th>Num. Adults</th><th>Num. Children</th><th>Order Date</th>
        </tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
        $username = mysql_result($result, $i, "username");
        $fname = mysql_result($result, $i, "fname");
        $lname = mysql_result($result, $i, "lname");
        $bookingref = mysql_result($result, $i, "cust_booking_ref");
        $flightid = mysql_result($result, $i, "flight_id");
        $depdate = mysql_result($result, $i, "departure_date");
        $price = mysql_result($result, $i, "total_price");
        $numadult = mysql_result($result, $i, "num_adults");
        $numchild = mysql_result($result, $i, "num_children");
        $orderdate = mysql_result($result, $i, "order_date");

        echo "<tr>
        <td>$username</td><td>$fname</td><td>$lname</td>
        <td>$bookingref</td><td>$flightid</td><td>$depdate</td>
        <td>$price</td><td>$numadult</td><td>$numchild</td><td>$orderdate</td>
        </tr>\n";
    }
    echo "</table>\n";
    
    unset($_SESSION['custsuccess']);
}

/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if (!$session->isAdmin()) {
    header("Location: ../main.php");
} else {
    /**
     * Administrator is viewing page, so display all
     * forms.
     */
?>
    <html>
        <head>
            <link rel="stylesheet" media="screen" type="text/css" href="../datepicker/css/datepicker.css" />
            <script type="text/javascript" src="../datepicker/js/jquery.js"></script>
            <script type="text/javascript" src="../datepicker/js/datepicker.js"></script>
            <script type="text/javascript" src="../datepicker/js/eye.js"></script>
            <script type="text/javascript" src="../datepicker/js/utils.js"></script>
            <script type="text/javascript" src="../datepicker/js/layout.js"></script>
            <style type="text/css" title="simplicity" media="all">
                @import "../layout/simplicity-style.css";
            </style>
            <title>WebAir Admin Centre</title>
        </head>
        <body>
            <h1>Admin Centre</h1>
            <font size="5" color="#ff0000">
                <b>::::::::::::::::::::::::::::::::::::::::::::</b></font>
            <font size="4">Logged in as <b><? echo $session->username; ?></b></font><br><br>
            Back to [<a href="admin.php">Admin Centre</a>]
            Back to [<a href="../main.php">Main Page</a>]
            Go to [<a href="usermgmt.php">User Management</a>]<br><br>
        <?
        if ($form->num_errors > 0) {
            echo "<font size=\"4\" color=\"#ff0000\">"
            . "!*** Error with request, please fix</font><br><br>";
        }
        ?>
        <hr /><br />

        <table>
            <tr>
                <td>
                    <h3>Today's Flight Allocation</h3>
                    <? displayTodaysBookings(); ?>
                    <br /><br />
                </td>
            </tr>
            <tr>
                <td>
                    <br /><hr /><br />
                    <h3>View Flight Details for a Specific Date</h3>
                    <? echo $form->error("printdate"); ?>
                    <form action="adminprocess.php" method="POST">
                        <span>Flight Date:</span><br />
                        <input class="printdate" name="printdate" id="printdate" value="<?php echo($today = date("Y-m-d")); ?>" />
                        <script type="text/javascript">

                            $('#printdate').DatePicker({
                                format:'Y-m-d',
                                date: $('#printdate').val(),
                                current: $('#printdate').val(),
                                starts: 1,
                                position: 'right',
                                onBeforeShow: function(){
                                    $('#printdate').DatePickerSetDate($('#printdate').val(), true);
                                },
                                onChange: function(formated, dates){
                                    $('#printdate').val(formated);
                                    if ($('#closeOnSelect input').attr('checked')) {
                                        $('#printdate').DatePickerHide();
                                    }
                                }
                            });
                        </script>
                        <br /><br />Flight Route:<br />
                        <select name="flight_id">
                            <option value="1">Bristol->Newcastle</option>
                            <option value="2">Newcastle->Bristol</option>
                            <option value="3">Bristol->Manchester</option>
                            <option value="4">Manchester->Bristol</option>
                            <option value="5">Bristol->Dublin</option>
                            <option value="6">Dublin->Glasgow</option>
                            <option value="7">Glasgow->Bristol</option>
                            <option value="8">Bristol->Glasgow</option>
                            <option value="9">Glasgow->Newcastle</option>
                            <option value="10">Newcastle->Manchester</option>
                            <option value="11">Manchester->Bristol</option>
                        </select>
                        <input type="hidden" name="subprintflight" value="1">
                        <input type="submit" value="View Details">
                    </form>

                    <table align="left" border="1" cellspacing="0" cellpadding="3">
                        <tr>
                            <th>Flight ID</th>
                            <th>Departure Date</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Departure Time</th>
                            <th>Arrival Time</th>
                            <th>Num. Seats Allocated</th>
                        </tr>
                        <tr>
                            <td><?php echo $_SESSION['printid']; ?></td>
                            <td><?php echo $_SESSION['printdate']; ?></td>
                            <td><?php echo $_SESSION['printorigin']; ?></td>
                            <td><?php echo $_SESSION['printdest']; ?></td>
                            <td><?php echo $_SESSION['printdeptime']; ?></td>
                            <td><?php echo $_SESSION['printarrtime']; ?></td>
                            <td><?php echo $_SESSION['printalloc']; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <br /><hr /><br />
                    <h3>Print Customer Details</h3>
                    <p>Enter a route and specific date to view customer details:</p>
                    <? echo $form->error("custdate"); ?>
                    <form action="adminprocess.php" method="POST">
                        Flight Date:<br />
                        <input class="custdate" name="custdate" id="printdate" value="<?php echo($today = date("Y-m-d")); ?>" />

                        <script type="text/javascript">

                            $('#printdate').DatePicker({
                                format:'Y-m-d',
                                date: $('#printdate').val(),
                                current: $('#printdate').val(),
                                starts: 1,
                                position: 'right',
                                onBeforeShow: function(){
                                    $('#printdate').DatePickerSetDate($('#printdate').val(), true);
                                },
                                onChange: function(formated, dates){
                                    $('#printdate').val(formated);
                                    if ($('#closeOnSelect input').attr('checked')) {
                                        $('#printdate').DatePickerHide();
                                    }
                                }
                            });
                        </script>
                        <br /><br />Flight Route:<br />
                        <select name="flight_id">
                            <option value="1">Bristol->Newcastle</option>
                            <option value="2">Newcastle->Bristol</option>
                            <option value="3">Bristol->Manchester</option>
                            <option value="4">Manchester->Bristol</option>
                            <option value="5">Bristol->Dublin</option>
                            <option value="6">Dublin->Glasgow</option>
                            <option value="7">Glasgow->Bristol</option>
                            <option value="8">Bristol->Glasgow</option>
                            <option value="9">Glasgow->Newcastle</option>
                            <option value="10">Newcastle->Manchester</option>
                            <option value="11">Manchester->Bristol</option>
                        </select>
                        <input type="hidden" name="subprintcust" value="1">
                        <input type="submit" value="View Details">
                    </form>
                    <?php
                    if (isset($_SESSION['custsuccess'])) {
                        printCustDetails();
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><br /><hr /><br /></td>
            </tr>
        </table>
    </body>
</html>
<?
                }
?>

