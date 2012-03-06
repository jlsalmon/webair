<?

/**
 * Process.php
 * 
 * The Process class is meant to simplify the task of processing
 * user submitted forms, redirecting the user to the correct
 * pages if errors are found, or if form is successful, either
 * way. Also handles the logout procedure.
 */
include("include/session.php");

class Process {
    /* Class constructor */

    function Process() {
        global $session;
        /* User submitted login form */
        if (isset($_POST['sublogin']) || isset($_POST['sublogin_big'])) {
            $this->procLogin();
        }
        /* User submitted booking form */ else if (isset($_POST['subprebook'])) {
            $this->procPreBook();
        }
        /* User reviewed booking details and wants to continue */ else if (isset($_POST['subbook'])) {
            $this->procBook();
        }
        /* User confirmed booking details */ else if (isset($_POST['subbookconfirm'])) {
            $this->procBookConfirm();
        }
        /* User submitted registration form */ else if (isset($_POST['subjoin'])) {
            $this->procRegister();
        }
        /* User submitted forgot password form */ else if (isset($_POST['subforgot'])) {
            $this->procForgotPass();
        }
        /* User submitted edit account form */ else if (isset($_POST['subedit'])) {
            $this->procEditAccount();
        }
        /* User submitted booking lookup form */ else if (isset($_POST['sublookup'])) {
            $this->procBookingLookup();
        }
        /* User wants to ckeck booking right after making it */ else if (isset($_POST['subchecknow'])) {
            $this->procCheckNow();
        }
        /**
         * The only other reason user should be directed here
         * is if he wants to logout, which means user is
         * logged in currently.
         */ else if ($session->logged_in) {
            $this->procLogout();
        }
        /**
         * Should not get here, which means user is viewing this page
         * by mistake and therefore is redirected.
         */ else {
            header("Location: index.php");
        }
    }

    /**
     * procLogin - Processes the user submitted login form, if errors
     * are found, the user is redirected to correct the information,
     * if not, the user is effectively logged in to the system.
     */
    function procLogin() {
        global $session, $form;
        /* Login attempt */
        $retval = $session->login($_POST['user'], $_POST['pass'], isset($_POST['remember']));

        /* Login successful */
        if ($retval) {
            header("Location: main.php");
        }
        /* Login failed */ else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

    /**
     * procLogout - Simply attempts to log the user out of the system
     * given that there is no logout form to process.
     */
    function procLogout() {
        global $session;
        $retval = $session->logout();
        header("Location: index.php");
    }

    /**
     * procPreBook - Processes the user submitted booking form, if user
     * is not logged in, the user is redirected to either log in or
     * register, if user is logged in, details of their booking is
     * displayed.
     */
    function procPreBook() {
        global $session, $form, $database;
        $session->clearBookingSession();

        /* Origin/Destiantion error checking */
        $suborigin = $_POST['origin'];
        $field = "origin";
        if ($suborigin == "default") {
            $form->setError($field, "* Please enter an origin");
        }
        $subdest = $_POST['destination'];
        $field = "destination";
        if (!$subdest) {
            $form->setError($field, "* Please enter a destination<br />");
        }

        /* Date error checking */
        $subdate = $_POST['departure_date'];
        $field = "departure_date";  //Use field name for date
        
        /* Check if date has passed */
        $today = date('Y-m-d');
        if ($subdate < $today) {
            $form->setError($field, "* Date is in the past");
        }
        /* Check if valid date */
        list($yyyy, $mm, $dd) = explode('-', $subdate);
        if (!checkdate($mm, $dd, $yyyy) || strlen($yyyy) != 4 || strlen($mm) != 2 || strlen($dd) != 2 ) {
            $form->setError($field, "* Date invalid");
        }
        if (!$subdate || strlen($subdate = trim($subdate)) == 0) {
            $form->setError($field, "* Date not entered");
        }
        /* Check if date is a Sunday */
        $chunk = explode('-', $subdate);
        $year = $chunk[0];
        $month = $chunk[1];
        $day = $chunk[2];
        $tstamp = mktime(0, 0, 0, $month, $day, $year);
        if (date('l', $tstamp) == 'Sunday') {
            $form->setError($field, "* Date cannot be a Sunday");
        }
        /* Check if date is more than 3 months in advance */
        $date = date('Y-m-d');
        $newdate = strtotime('+3 month', strtotime($date));
        $newdate = date('Y-m-d', $newdate);
        if ($subdate > $newdate) {
            $form->setError($field, "* Date cannot be more than 3 months in advance");
        }

        /* Passenger error checking */
        $subadult = $_POST['num_adults'];
        $field = "num_adults";
        if ($subadult == "0") {
            $form->setError($field, "* Please enter number of passengers");
        }
        
        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            $field = 'destination';
            $form->setValue($field, $_POST['destination']);
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);  //Errors with form
        } else {
            /* Set session variables for later use */
            $_SESSION['flightorigin'] = $_POST['origin'];
            $_SESSION['flightdest'] = $_POST['destination'];
            $_SESSION['flightdepdate'] = $_POST['departure_date'];
            $_SESSION['flightnumadult'] = $_POST['num_adults'];
            $_SESSION['flightnumchild'] = $_POST['num_children'];
            $times = $database->getFlightTimes();
            if (!$times) {
                die("getFlightTimes failed");
            } else {
                $_SESSION['flightdeptime'] = $times['departure_time'];
                $_SESSION['flightarrtime'] = $times['arrival_time'];
            }

            $_SESSION['prebook'] = true;
            header("Location: main.php");
        }
    }

    /**
     * procBook - Checks if the requested flight is available. If it 
     * isn't, booking fails and apologies are given
     * to the user. If it is, user is redirected to confirm. Also
     * calculates price of flight.
     */
    function procBook() {
        global $session, $database;

        /* Calculate total number of seats required */
        $numseats = $_SESSION['flightnumadult'] + $_SESSION['flightnumchild'];

        /* Check flight availability */
        $avail = $database->checkAvailability($_SESSION['flightorigin'],
                        $_SESSION['flightdest'],
                        $_SESSION['flightdepdate'],
                        $numseats);

        /* Query failed, */
        if ($avail == 2) {
            $_SESSION['booksuccess'] = false;
            header("Location: main.php");
        }
        /* Flight full */ else if ($avail == 1) {
            $_SESSION['booksuccess'] = false;
            header("Location: book.php");
        }
        /* Flight available, */ else {
            $_SESSION['booksuccess'] = true;
            header("Location: book.php");

            /* Calculate total price of all seats */
            $_SESSION['price'] = $database->calcPrice($_SESSION['flightorigin'], $_SESSION['flightdest']);
        }
    }

    /**
     * procBookConfirm - Completes booking by generating a booking reference,
     * adding relevant information to the database, displaying the booking
     * reference on the page and also emailing the user with confirmation.
     */
    function procBookConfirm() {
        global $session, $database, $mailer, $form;

        /* Check T&C were accepted */
        $subtermsconfirm = $_POST['termsconfirm'];
        $field = "termsconfirm";
        if (!isset($_POST['termsconfirm'])) {
            $form->setError($field, "* Please accept the terms and conditions to continue");
        }

        if ($form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);  //Errors with form
        } else {
            /* Generate unique booking reference */
            $bookingref = $session->generateBookingRef();

            /* Get the customer id */
            $customer_id = $database->getCustomerId($session->username);

            /* Get the flight id */
            $flight_id = $database->getFlightId($_SESSION['flightorigin'], $_SESSION['flightdest']);

            /* Add the informatio to the database */
            if ($database->addBookingInfo($customer_id, $bookingref, $flight_id,
                            $_SESSION['flightdepdate'],
                            $_SESSION['price'],
                            $_SESSION['flightnumadult'],
                            $_SESSION['flightnumchild'])) {
                /* Query success */
                $_SESSION['bookconfirmed'] = true;
                $_SESSION['bookingref'] = $bookingref;
                /* Update seats allocated */
                if ($database->updateSeatsAlloc($flight_id, $_SESSION['flightdepdate'])) {
                    header("Location: success.php");
                }
                else
                    die("updateSeatsAlloc failed");

                /* Send email confirmation of the booking */
                $email = $database->getUserInfo($session->username);
                $email = $email['email'];
                $mailer->sendBookingConfirmation(
                        $email,
                        $session->username,
                        $bookingref,
                        $_SESSION['flightorigin'],
                        $_SESSION['flightdest'],
                        $_SESSION['flightdepdate'],
                        $_SESSION['price'],
                        $_SESSION['flightnumadult'],
                        $_SESSION['flightnumchild']);

                /* Clear booking session variables */
                $session->clearBookingSession();
            }
            /* Query failed */ else
                die("addBookingInfo failed");
        }
    }

    /**
     * procRegister - Processes the user submitted registration form,
     * if errors are found, the user is redirected to correct the
     * information, if not, the user is effectively registered with
     * the system and an email is (optionally) sent to the newly
     * created user.
     */
    function procRegister() {
        global $session, $form;
        /* Convert username to all lowercase (by option) */
        if (ALL_LOWERCASE) {
            $_POST['user'] = strtolower($_POST['user']);
        }

        /* Registration attempt */
        $retval = $session->register($_POST['fname'], $_POST['lname'], $_POST['user'], $_POST['pass'], $_POST['passconf'], $_POST['email']);

        /* Registration Successful, attempt login */
        if ($retval == 0) {
            $login = $session->login($_POST['user'], $_POST['pass'], isset($_POST['remember']));
            if ($login) {
                $_SESSION['reguname'] = $_POST['user'];
                $_SESSION['regsuccess'] = true;
                header("Location: " . $session->referrer);
            }
        }
        /* Error found with form */ else if ($retval == 1) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
        /* Registration attempt failed */ else if ($retval == 2) {
            $_SESSION['reguname'] = $_POST['user'];
            $_SESSION['regsuccess'] = false;
            header("Location: " . $session->referrer);
        }
    }

    /**
     * procForgotPass - Validates the given username then if
     * everything is fine, a new password is generated and
     * emailed to the address the user gave on sign up.
     */
    function procForgotPass() {
        global $database, $session, $mailer, $form;
        /* Username error checking */
        $subuser = $_POST['user'];
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Username not entered");
        } else {
            /* Make sure username is in database */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5 || strlen($subuser) > 30 ||
                    !eregi("^([0-9a-z])+$", $subuser) ||
                    (!$database->usernameTaken($subuser))) {
                $form->setError($field, "* Username does not exist");
            }
        }

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
        }
        /* Generate new password and email it to user */ else {
            /* Generate new password */
            $newpass = $session->generateRandStr(8);

            /* Get email of user */
            $usrinf = $database->getUserInfo($subuser);
            $email = $usrinf['email'];

            /* Attempt to send the email with new password */
            if ($mailer->sendNewPass($subuser, $email, $newpass)) {
                /* Email sent, update database */
                $database->updateUserField($subuser, "password", md5($newpass));
                $_SESSION['forgotpass'] = true;
            }
            /* Email failure, do not change password */ else {
                $_SESSION['forgotpass'] = false;
            }
        }

        header("Location: " . $session->referrer);
    }

    /**
     * procEditAccount - Attempts to edit the user's account
     * information, including the password, which must be verified
     * before a change is made.
     */
    function procEditAccount() {
        global $session, $form;
        /* Account edit attempt */
        $retval = $session->editAccount($_POST['curpass'], $_POST['newpass'], $_POST['email']);

        /* Account edit successful */
        if ($retval) {
            $_SESSION['useredit'] = true;
            header("Location: " . $session->referrer);
        }
        /* Error found with form */ else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

    /**
     * procBookingLookup - Attempts to get the user's previous
     * booking history based on their username and booking reference
     * number.
     */
    function procBookingLookup() {
        global $session, $form, $database;

        $retval = $session->bookingLookup($_POST['user'], $_POST['bookingref']);

        /* Error found with form */ if ($retval == 1) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        } else {
            if ($retval) {
                $_SESSION['lookupsuccess'] = true;
                $_SESSION['bookingref'] = $_POST['bookingref'];
                $_SESSION['l_origin'] = $retval['origin'];
                $_SESSION['l_dest'] = $retval['destination'];
                $_SESSION['l_deptime'] = $retval['departure_time'];
                $_SESSION['l_arrtime'] = $retval['arrival_time'];
                $_SESSION['l_custid'] = $retval['customer_id'];
                $_SESSION['l_depdate'] = $retval['departure_date'];
                $_SESSION['l_numadult'] = $retval['num_adults'];
                $_SESSION['l_numchild'] = $retval['num_children'];
                $_SESSION['l_orderdate'] = $retval['order_date'];

                header("Location: lookup.php");
            }
        }
    }

    /**
     * procCheckNow - Attempts to get the flight details
     * of the flight the user just booked, as they have
     * requested.
     */
    function procCheckNow() {
        global $session, $form, $database;

        $retval = $session->checkBookingNow($session->username, $_SESSION['bookingref']);

        /* Error found with form */
        if ($retval == 1) {
            die("ffs");
        } else {
            if ($retval) {
                $_SESSION['lookupsuccess'] = true;
                $_SESSION['l_origin'] = $retval['origin'];
                $_SESSION['l_dest'] = $retval['destination'];
                $_SESSION['l_deptime'] = $retval['departure_time'];
                $_SESSION['l_arrtime'] = $retval['arrival_time'];
                $_SESSION['l_custid'] = $retval['customer_id'];
                $_SESSION['l_depdate'] = $retval['departure_date'];
                $_SESSION['l_numadult'] = $retval['num_adults'];
                $_SESSION['l_numchild'] = $retval['num_children'];
                $_SESSION['l_orderdate'] = $retval['order_date'];

                header("Location: lookup.php");
            }
        }
    }

}

;

/* Initialize process */
$process = new Process;
?>
