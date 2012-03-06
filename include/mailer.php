<?

/**
 * Mailer.php
 *
 * The Mailer class is meant to simplify the task of sending
 * emails to users. Note: this email system will not work
 * if the server is not setup to send mail.
 *
 */
class Mailer {

    /**
     * sendWelcome - Sends a welcome message to the newly
     * registered user, also supplying the username and
     * password.
     */
    function sendWelcome($user, $email, $pass) {
        $from = "From: " . EMAIL_FROM_NAME . " <" . EMAIL_FROM_ADDR . ">";
        $subject = "WebAir - Welcome!";
        $body = $user . ",\n\n"
                . "Welcome! You've just registered with WebAir "
                . "with the following information:\n\n"
                . "Username: " . $user . "\n"
                . "Password: " . $pass . "\n\n"
                . "If you ever lose or forget your password, a new "
                . "password will be generated for you and sent to this "
                . "email address, if you would like to change your "
                . "email address you can do so by going to the "
                . "My Account page after signing in.\n\n"
                . "- WebAir";

        return mail($email, $subject, $body, $from);
    }

    /**
     * sendNewPass - Sends the newly generated password
     * to the user's email address that was specified at
     * sign-up.
     */
    function sendNewPass($user, $email, $pass) {
        $from = "From: " . EMAIL_FROM_NAME . " <" . EMAIL_FROM_ADDR . ">";
        $subject = "WebAir - Your new password";
        $body = $user . ",\n\n"
                . "We've generated a new password for you at your "
                . "request, you can use this new password with your "
                . "username to log in to WebAir.\n\n"
                . "Username: " . $user . "\n"
                . "New Password: " . $pass . "\n\n"
                . "It is recommended that you change your password "
                . "to something that is easier to remember, which "
                . "can be done by going to the My Account page "
                . "after signing in.\n\n"
                . "- WebAir";

        return mail($email, $subject, $body, $from);
    }

    /**
     * sendBookingConfirmation() - TODO
     */
    function sendBookingConfirmation($email, $user, $bookingref, $origin, $destination, $departure_date, $price, $num_adults, $num_children) {

        $from = "From: " . EMAIL_FROM_NAME . " <" . EMAIL_FROM_ADDR . ">";
        $subject = "WebAir - Booking Reference: ".$bookingref;
        $body = $user . ",\n\n"
                . "Thank you for your recent booking. "
                . "The details of your flight are as follows:\n\n"
                . "Booking Ref:\t" . $bookingref . "\n\n"
                . "From:\t\t" . $origin . "\n"
                . "To:\t\t" . $destination . "\n"
                . "Departure Date:\t" . $departure_date . "\n"
                . "Adults:\t\t" . $num_adults . "\n"
                . "Children:\t" . $num_children . "\n"
                . "Total Price:\t£" . $price . "\n\n"
                . "Remember, you can check the details of your "
                . "flight at any time on the WebAir home page.\n\n"
                . "We look forward to having you fly with us.\n"
                . "Kind regards,\n\n"
                . "- WebAir";

        return mail($email, $subject, $body, $from);
    }

}

;

/* Initialize mailer object */
$mailer = new Mailer;
?>
