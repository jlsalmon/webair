<?
include("../include/session.php");

/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if (!$session->isAdmin()) {
    header("Location: ../main.php");
} else {
    /**
     * Administrator is viewing page, so display links
     */
?>
    <html>
        <head>
            <title>WebAir Admin Centre</title>
            <style type="text/css" title="simplicity" media="all">
                @import "/layout/simplicity-style.css";
            </style>    
        </head>
        <body>
            <h1>Admin Centre</h1>
            <font size="5" color="#ff0000">
                <b>::::::::::::::::::::::::::::::::::::::::::::</b></font>
            <font size="4">Logged in as <b><? echo $session->username; ?></b></font><br><br>
            Go to [<a href="usermgmt.php">User Management</a>] <br><br>
            Go to [<a href="flightmgmt.php">Flight Management</a>]<br /><br />
            Back to [<a href="../main.php">Main Page</a>]

        </body>
    </html>
<?
}
?>

