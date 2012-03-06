<?

/**
 * Database.php
 * 
 * The Database class is meant to simplify the task of accessing
 * information from the website's database.
 *
 */
include("constants.php");

class MySQLDB {

    var $connection;         //The MySQL database connection
    var $num_active_users;   //Number of active users viewing site
    var $num_active_guests;  //Number of active guests viewing site
    var $num_members;        //Number of signed-up users

    /* Note: call getNumMembers() to access $num_members! */

    /* Class constructor */

    function MySQLDB() {
        /* Make connection to database */
        $this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
        mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());

        /**
         * Only query database to find out number of members
         * when getNumMembers() is called for the first time,
         * until then, default value set.
         */
        $this->num_members = -1;

        if (TRACK_VISITORS) {
            /* Calculate number of users at site */
            $this->calcNumActiveUsers();

            /* Calculate number of guests at site */
            $this->calcNumActiveGuests();
        }
    }

    /**
     * checkAvailability - Checks whether or not the requested
     * flight has enough seats available, by determining the
     * flight id from the origin/destination and checking against
     * the requested date.
     * If the flight is full, 1 is returned. If there are seats
     * available, 0 is returned.
     */
    function checkAvailability($origin, $destination, $departure_date, $numseats) {

        $q = "SELECT " . TBL_FLIGHT_ALLOC . ".seats_alloc FROM " . TBL_FLIGHT_ALLOC . ", " . TBL_FLIGHT_INFO
                . " WHERE " . TBL_FLIGHT_ALLOC . ".flight_id = " . TBL_FLIGHT_INFO . ".flightid"
                . " AND " . TBL_FLIGHT_INFO . ".origin = \"$origin\""
                . " AND " . TBL_FLIGHT_INFO . ".destination = \"$destination\""
                . " AND " . TBL_FLIGHT_ALLOC . ".flight_date = \"$departure_date\"";
        $result = mysql_query($q, $this->connection);

        if (!$result || (mysql_numrows($result) < 1)) {
            return 2; //Indicates query failure
        }

        $dbarray = mysql_fetch_array($result);

        /* Validate that seats are available */
        if ($numseats < ( MAX_SEATS - $dbarray['seats_alloc'])) {
            return 0; //Success! Specified number of seats are available
        } else {
            return 1;
        }//No seats available
    }

    /**
     * calcPrice - Calculates the price of the requested flight
     * based on the standard fare.
     */
    function calcPrice($origin, $destination) {
        $q = "select seat_cost from " . TBL_FLIGHT_INFO . " where origin = '$origin' and destination = '$destination'";
        $result = mysql_query($q, $this->connection);

        if (!$result || (mysql_numrows($result) < 1)) {
            die("calcPrice failed"); //Indicates query failure
        }
        $dbarray = mysql_fetch_array($result);

        /* Kids fly free, don't include them */
        $price = $dbarray['seat_cost'] * $_SESSION['flightnumadult'];

        return $price;
    }

    /**
     * addBookingInfo -Adds the necessary infromation
     * to complete the booking to the database.
     */
    function addBookingInfo($customer_id, $cust_booking_ref, $flightid, $departure_date, $total_price, $num_adults, $num_children) {
        $order_date = date("Y-m-d");

        $q = "INSERT INTO " . TBL_CUST_BOOKING . " VALUES
        ($customer_id, $cust_booking_ref, $flightid, '$departure_date', $total_price, $num_adults, $num_children, '$order_date')";
        return mysql_query($q, $this->connection);
    }

    /**
     * updateSeatsAlloc - After successful order, updates
     * the seats allocated for the ordered flight.
     */
    function updateSeatsAlloc($flight_id, $departure_date) {
        $numseats = $_SESSION['flightnumadult'] + $_SESSION['flightnumchild'];
        $q = "select seats_alloc from " . TBL_FLIGHT_ALLOC . " where flight_id = $flight_id and flight_date = '$departure_date'";
        $result = mysql_query($q, $this->connection);
        if (!$result || (mysql_numrows($result) < 1)) {
            return 2; //Indicates query failure
        }
        $dbarray = mysql_fetch_array($result);

        $numseats += $dbarray['seats_alloc'];

        $q = "UPDATE " . TBL_FLIGHT_ALLOC . " SET seats_alloc = $numseats WHERE flight_id = $flight_id and flight_date = '$departure_date'";
        return mysql_query($q, $this->connection);
    }

    /**
     * getFlightTimes - Returns an array containing the arrival
     * and departure times for a flight.
     */
    function getFlightTimes() {
        $origin = $_SESSION['flightorigin'];
        $dest = $_SESSION['flightdest'];

        $q = "select departure_time, arrival_time from " . TBL_FLIGHT_INFO . " where origin = '$origin' and destination = '$dest'";
        $result = mysql_query($q, $this->connection);
        if (!$result || (mysql_numrows($result) < 1)) {
            die("ewww failure"); //Indicates query failure
        }
        $dbarray = mysql_fetch_array($result);
        return $dbarray;
    }

    /**
     * confirmUserPass - Checks whether or not the given
     * username is in the database, if so it checks if the
     * given password is the same password in the database
     * for that user. If the user doesn't exist or if the
     * passwords don't match up, it returns an error code
     * (1 or 2). On success it returns 0.
     */
    function confirmUserPass($username, $password) {
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }

        /* Verify that user is in database */
        $q = "SELECT password FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysql_query($q, $this->connection);
        if (!$result || (mysql_numrows($result) < 1)) {
            return 1; //Indicates username failure
        }

        /* Retrieve password from result, strip slashes */
        $dbarray = mysql_fetch_array($result);
        $dbarray['password'] = stripslashes($dbarray['password']);
        $password = stripslashes($password);

        /* Validate that password is correct */
        if ($password == $dbarray['password']) {
            return 0; //Success! Username and password confirmed
        } else {
            return 2; //Indicates password failure
        }
    }

    /**
     * confirmUserID - Checks whether or not the given
     * username is in the database, if so it checks if the
     * given userid is the same userid in the database
     * for that user. If the user doesn't exist or if the
     * userids don't match up, it returns an error code
     * (1 or 2). On success it returns 0.
     */
    function confirmUserID($username, $userid) {
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }

        /* Verify that user is in database */
        $q = "SELECT userid FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysql_query($q, $this->connection);
        if (!$result || (mysql_numrows($result) < 1)) {
            return 1; //Indicates username failure
        }

        /* Retrieve userid from result, strip slashes */
        $dbarray = mysql_fetch_array($result);
        $dbarray['userid'] = stripslashes($dbarray['userid']);
        $userid = stripslashes($userid);

        /* Validate that userid is correct */
        if ($userid == $dbarray['userid']) {
            return 0; //Success! Username and userid confirmed
        } else {
            return 2; //Indicates userid invalid
        }
    }

    /**
     * usernameTaken - Returns true if the username has
     * been taken by another user, false otherwise.
     */
    function usernameTaken($username) {
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }
        $q = "SELECT username FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysql_query($q, $this->connection);
        return (mysql_numrows($result) > 0);
    }

    /**
     * usernameBanned - Returns true if the username has
     * been banned by the administrator.
     */
    function usernameBanned($username) {
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }
        $q = "SELECT username FROM " . TBL_BANNED_USERS . " WHERE username = '$username'";
        $result = mysql_query($q, $this->connection);
        return (mysql_numrows($result) > 0);
    }

    /**
     * addNewUser - Inserts the given (username, password, email)
     * info into the database. Appropriate user level is set.
     * Returns true on success, false otherwise.
     */
    function addNewUser($fname, $lname, $username, $password, $email) {
        $time = time();
        /* If admin sign up, give admin user level */
        if (strcasecmp($username, ADMIN_NAME) == 0) {
            $ulevel = ADMIN_LEVEL;
        } else {
            $ulevel = USER_LEVEL;
        }
        /* Generate customer id */
        srand(time());
        $customer_id = (rand(100000, 999999));

        $q = "INSERT INTO " . TBL_USERS . " VALUES ('$username', '$fname', '$lname', '$password', '0', $ulevel, '$email', $time, $customer_id)";
        return mysql_query($q, $this->connection);
    }

    /**
     * getcustomerId - Returns the customer id of the specified user.
     */
    function getCustomerId($username) {
        $q = "select customer_id from " . TBL_USERS . " where username = '$username'";

        $result = mysql_query($q, $this->connection);

        if (!$result || (mysql_numrows($result) < 1)) {
            die("getCustomerId failed"); //Indicates query failure
        }
        $dbarray = mysql_fetch_array($result);
        return $dbarray['customer_id'];
    }

    /**
     * getFlightId - Returns the flight id of the requested flight by
     * querying the origin and destination.
     */
    function getFlightId($origin, $destination) {
        $q = "select flightid from " . TBL_FLIGHT_INFO . " where origin = '$origin' and destination = '$destination'";

        $result = mysql_query($q, $this->connection);

        if (!$result || (mysql_numrows($result) < 1)) {
            die("getFlightId failed"); //Indicates query failure
        }
        $dbarray = mysql_fetch_array($result);
        return $dbarray['flightid'];
    }

    /**
     * getBookingInfo - Returns the result array from a mysql
     * query asking for all booking information stored regarding
     * the given username. If the query fails, script dies.
     */
    function getBookingInfo($bookingref, $customer_id) {
        $q = "select " . TBL_FLIGHT_INFO . ".origin, " . TBL_FLIGHT_INFO . ".destination, "
                . TBL_FLIGHT_INFO . ".departure_time, " . TBL_FLIGHT_INFO . ".arrival_time, "
                . TBL_CUST_BOOKING . ".customer_id, "
                . TBL_CUST_BOOKING . ".departure_date, cust_booking.num_adults, "
                . TBL_CUST_BOOKING . ".num_children, "
                . TBL_CUST_BOOKING . ".order_date "
                . "from " . TBL_FLIGHT_INFO . ", " . TBL_CUST_BOOKING . " "
                . "where " . TBL_FLIGHT_INFO . ".flightid = " . TBL_CUST_BOOKING . ".flight_id "
                . "and customer_id = $customer_id "
                . "and cust_booking_ref = $bookingref";
        $result = mysql_query($q, $this->connection);
        /* Error occurred, return given name by default */
        if (!$result || (mysql_numrows($result) < 1)) {
            die("getBookingInfo failed");
        }
        /* Return result array */
        $dbarray = mysql_fetch_array($result);
        return $dbarray;
    }

    /**
     * confirmBookingRef - Checks the database to make sure the booking
     * ref entered actually exists in the database.
     */
    function confirmBookingRef($bookingref) {
        $q = "SELECT cust_booking_ref from " . TBL_CUST_BOOKING . " where cust_booking_ref = $bookingref";
        $result = mysql_query($q, $this->connection);
        if (!$result || (mysql_numrows($result) < 1)) {
            return 0;
        }
        else
            return 1;
    }

    /**
     * getFlightDetails - Stores the flight details for the selected date
     * and flight id in an array and returns it.
     */
    function getFlightDetails($date, $flight_id) {
        $q = "select " . TBL_FLIGHT_INFO . ".flightid, " . TBL_FLIGHT_INFO . ".departure_time, "
                . TBL_FLIGHT_INFO . ".arrival_time, " . TBL_FLIGHT_INFO . ".origin, "
                . TBL_FLIGHT_INFO . ".destination, " . TBL_FLIGHT_ALLOC . ".seats_alloc, " . TBL_FLIGHT_ALLOC . ".flight_date "
                . "from " . TBL_FLIGHT_INFO . ", " . TBL_FLIGHT_ALLOC . " "
                . "where " . TBL_FLIGHT_INFO . ".flightid = " . TBL_FLIGHT_ALLOC . ".flight_id "
                . "and " . TBL_FLIGHT_ALLOC . ".flight_date = '$date' "
                . "and " . TBL_FLIGHT_INFO . ".flightid = $flight_id";
        $result = mysql_query($q, $this->connection);
        /* Error occurred */
        if (!$result || (mysql_numrows($result) < 1)) {
            die("getFlightDetails failed");
        }
        /* Return result array */
        $dbarray = mysql_fetch_array($result);
        return $dbarray;
    }

    /**
     * getCustDetails - same as getFlightDetails but returns
     * customer details in the array instead
     */
    function getCustDetails($date, $flight_id) {
        $q = "select " . TBL_USERS . ".username, " . TBL_USERS . ".fname, "
        ."users.lname, " . TBL_CUST_BOOKING . ".cust_booking_ref, "
        . TBL_CUST_BOOKING . ".flight_id, " . TBL_CUST_BOOKING . ".departure_date, "
        . TBL_CUST_BOOKING . ".total_price, " . TBL_CUST_BOOKING . ".num_adults, "
        . TBL_CUST_BOOKING . ".num_children, " . TBL_CUST_BOOKING . ".order_date "
        ."from " . TBL_USERS . ", " . TBL_CUST_BOOKING . " "
        ."where " . TBL_USERS . ".customer_id = " . TBL_CUST_BOOKING . ".customer_id "
        ."and " . TBL_CUST_BOOKING . ".flight_id = $flight_id "
        ."and " . TBL_CUST_BOOKING . ".departure_date = '$date'";
        $result = mysql_query($q, $this->connection);
        /* Error occurred */
        if (!$result) {
            die("getCustDetails failed");
        } else {
            return $result;
        }
    }

    /**
     * updateUserField - Updates a field, specified by the field
     * parameter, in the user's row of the database.
     */
    function updateUserField($username, $field, $value) {
        $q = "UPDATE " . TBL_USERS . " SET " . $field . " = '$value' WHERE username = '$username'";
        return mysql_query($q, $this->connection);
    }

    /**
     * getUserInfo - Returns the result array from a mysql
     * query asking for all information stored regarding
     * the given username. If query fails, NULL is returned.
     */
    function getUserInfo($username) {
        $q = "SELECT * FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysql_query($q, $this->connection);
        /* Error occurred, return given name by default */
        if (!$result || (mysql_numrows($result) < 1)) {
            return NULL;
        }
        /* Return result array */
        $dbarray = mysql_fetch_array($result);
        return $dbarray;
    }

    /**
     * getNumMembers - Returns the number of signed-up users
     * of the website, banned members not included. The first
     * time the function is called on page load, the database
     * is queried, on subsequent calls, the stored result
     * is returned. This is to improve efficiency, effectively
     * not querying the database when no call is made.
     */
    function getNumMembers() {
        if ($this->num_members < 0) {
            $q = "SELECT * FROM " . TBL_USERS;
            $result = mysql_query($q, $this->connection);
            $this->num_members = mysql_numrows($result);
        }
        return $this->num_members;
    }

    /**
     * calcNumActiveUsers - Finds out how many active users
     * are viewing site and sets class variable accordingly.
     */
    function calcNumActiveUsers() {
        /* Calculate number of users at site */
        $q = "SELECT * FROM " . TBL_ACTIVE_USERS;
        $result = mysql_query($q, $this->connection);
        $this->num_active_users = mysql_numrows($result);
    }

    /**
     * calcNumActiveGuests - Finds out how many active guests
     * are viewing site and sets class variable accordingly.
     */
    function calcNumActiveGuests() {
        /* Calculate number of guests at site */
        $q = "SELECT * FROM " . TBL_ACTIVE_GUESTS;
        $result = mysql_query($q, $this->connection);
        $this->num_active_guests = mysql_numrows($result);
    }

    /**
     * addActiveUser - Updates username's last active timestamp
     * in the database, and also adds him to the table of
     * active users, or updates timestamp if already there.
     */
    function addActiveUser($username, $time) {
        $q = "UPDATE " . TBL_USERS . " SET timestamp = '$time' WHERE username = '$username'";
        mysql_query($q, $this->connection);

        if (!TRACK_VISITORS)
            return;
        $q = "REPLACE INTO " . TBL_ACTIVE_USERS . " VALUES ('$username', '$time')";
        mysql_query($q, $this->connection);
        $this->calcNumActiveUsers();
    }

    /* addActiveGuest - Adds guest to active guests table */

    function addActiveGuest($ip, $time) {
        if (!TRACK_VISITORS)
            return;
        $q = "REPLACE INTO " . TBL_ACTIVE_GUESTS . " VALUES ('$ip', '$time')";
        mysql_query($q, $this->connection);
        $this->calcNumActiveGuests();
    }

    /* These functions are self explanatory, no need for comments */

    /* removeActiveUser */

    function removeActiveUser($username) {
        if (!TRACK_VISITORS)
            return;
        $q = "DELETE FROM " . TBL_ACTIVE_USERS . " WHERE username = '$username'";
        mysql_query($q, $this->connection);
        $this->calcNumActiveUsers();
    }

    /* removeActiveGuest */

    function removeActiveGuest($ip) {
        if (!TRACK_VISITORS)
            return;
        $q = "DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE ip = '$ip'";
        mysql_query($q, $this->connection);
        $this->calcNumActiveGuests();
    }

    /* removeInactiveUsers */

    function removeInactiveUsers() {
        if (!TRACK_VISITORS)
            return;
        $timeout = time() - USER_TIMEOUT * 60;
        $q = "DELETE FROM " . TBL_ACTIVE_USERS . " WHERE timestamp < $timeout";
        mysql_query($q, $this->connection);
        $this->calcNumActiveUsers();
    }

    /* removeInactiveGuests */

    function removeInactiveGuests() {
        if (!TRACK_VISITORS)
            return;
        $timeout = time() - GUEST_TIMEOUT * 60;
        $q = "DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE timestamp < $timeout";
        mysql_query($q, $this->connection);
        $this->calcNumActiveGuests();
    }

    /**
     * query - Performs the given query on the database and
     * returns the result, which may be false, true or a
     * resource identifier.
     */
    function query($query) {
        return mysql_query($query, $this->connection);
    }

}

;

/* Create database connection */
$database = new MySQLDB;
?>
