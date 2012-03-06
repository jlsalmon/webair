<?php include("include/session.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="en-GB" />
        <meta name="author" content="Justin Lewis" />
        <meta name="abstract" content="WebAir, cheap flights across the United Kingdom" />
        <meta name="description" content="WebAir provides cheap, reliable airline flights from many destinations in the UK" />
        <meta name="keywords" content="WebAir, flights, UK, airline" />
        <meta name="distribution" content="global" />
        <meta name="revisit-after" content="1 days" />
        <meta name="copyright" content="All content (c) WebAir" />

        <title>WebAir | Edit My Information</title>

        <style type="text/css" title="simplicity" media="all">
            @import "layout/simplicity-style.css";
        </style>
        <!--[if IE]>
        <style type="text/css" media="all">
          @import "layout/ie-diff.css";
        </style>
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="layout/print.css" media="print" />

        <link rel="icon" href="http://www.web-air.co.uk/layout/images/favicon.ico" type="image/x-icon" />

    </head>

    <body>

        <div id="window">
            <div id="container">

                <div id="main-head">
                    <a href="index.php" title="WebAir Homepage"><img alt="webair logo"  src="layout/images/webair-logo-small.png"/></a>
                    <table class="smalltext" style="font-size:10px; vertical-align:top; clear:both; float:right;">
                        <tr>
                            <td valign="top">
                                <?
                                /**
                                 * User has already logged in, so display relavent links, including
                                 * a link to the admin center if the user is an administrator.
                                 */
                                if ($session->logged_in) {
                                    echo "Logged in as <b>$session->username</b>.";
                                    if ($session->isAdmin()) {
                                        echo "[<a href=\"admin/admin.php\">Admin Center</a>] &nbsp;&nbsp;";
                                    }
                                    echo "[<a href=\"process.php\">Logout</a>]";
                                } else {
                                ?>
                                    <form action="process.php" method="post" style="float:right;">
                                    <?
                                    /**
                                     * User not logged in, display the login form.
                                     * If user has already tried to login, but errors were
                                     * found, display the total number of errors.
                                     * If errors occurred, they will be displayed.
                                     */
                                    if ($form->num_errors > 0) {
                                        echo "<span><font color=\"#ff0000\">" . $form->num_errors . " error(s) found</font></span>";
                                    }
                                    ?>


                                    <span>Login: </span>
                                    <input type="text" size="5" class="smalltext" name="user" maxlength="30" value="Username" onfocus="this.value='';" />
                                    <input type="password" size="5" class="smalltext" name="pass" maxlength="30" value="Password" onfocus="this.value='';"/>
                                    <input type="checkbox" name="remember" class="smalltext" <?
                                    if ($form->value("remember") != "") {
                                        echo "checked";
                                    }
                                    ?> />
                                    <span>Remember me</span>
                                    <input type="hidden" name="sublogin" value="1" />
                                    <input type="submit" value="Login" style="height: 1.7em; width: 4em; background-color:#3B5998; font-size:8pt; color:white;" />

                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <? echo $form->error("user"); ?><br /><? echo $form->error("pass"); ?>
                                </td>
                            </tr>

                        <?
                                }
                        ?></table>
                        </div>

                        <div id="navigation">
                            <ul>
                                <!-- The link you call "active" will show up as a darker tab -->
                                <!--[if IE 6]><li></li><![endif]-->
                                <li><a href="index.php">Home</a></li>
                                <li><a href="main.php">My Bookings</a></li>
                                <li class="active"><span>My Account</span></li>
                                <li><a href="info.php">Travel Information</a></li>
                                <li><a href="register.php">Register</a></li>
                                <li><a href="about.php">About</a></li>
                            </ul>
                        </div>

                        <div id="main">
                            <div id="outer-prettification">
                                <div id="inner-prettification">

                                    <div id="header">
                                        <h1 id="title"><a href="useredit.php"> </a></h1>
                                    </div>

                                    <div id="contents">

                                        <div id="showcase">
                                            <table class="statistics" >
                                        <?
                                        /**
                                         * User has submitted form without errors and user's
                                         * account has been edited successfully.
                                         */
                                        if (isset($_SESSION['useredit'])) {
                                            unset($_SESSION['useredit']);

                                            echo "<tr><td><h2>User Account Edit Success!</h2></td></tr>";
                                            echo "<tr><td><b>$session->username</b>, your account has been successfully updated. "
                                            . "<br /><br />-> <a href=\"userinfo.php?user=$session->username\">Back to My Account</a><br /><br /><br /></td></tr></table>";
                                        } else {
                                        ?>

                                        <?
                                            /**
                                             * If user is not logged in, then do not display anything.
                                             * If user is logged in, then display the form to edit
                                             * account information, with the current email address
                                             * already in the field.
                                             */
                                            if ($session->logged_in) {
                                        ?>

                                                <tr><td><h2 id="title">User Account Edit : <? echo $session->username; ?></h2></td></tr>
                                        <?
                                                if ($form->num_errors > 0) {
                                                    echo "<tr><td><font size=\"2\" color=\"#ff0000\"><span>" . $form->num_errors . " error(s) found</span></font></td><tr>";
                                                }
                                        ?>
                                                <form action="process.php" method="POST">

                                                    <tr>
                                                        <td>Current Password:</td>
                                                        <td><input type="password" name="curpass" maxlength="30" value="<? echo $form->value("curpass"); ?>" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td><? echo $form->error("curpass"); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>New Password:</td>
                                                        <td><input type="password" name="newpass" maxlength="30" value="<? echo $form->value("newpass"); ?>" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td><? echo $form->error("newpass"); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email:</td>
                                                        <td><input type="text" name="email" maxlength="50" value="
                                                    <?
                                                    if ($form->value("email") == "") {
                                                        echo $session->userinfo['email'];
                                                    } else {
                                                        echo $form->value("email");
                                                    }
                                                    ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><? echo $form->error("email"); ?></td>
                                            </tr>
                                            <tr><td colspan="2" align="right">
                                                    <input type="hidden" name="subedit" value="1" />
                                                    <input type="submit" value="Edit Account" /></td></tr>
                                            <tr><td colspan="2" align="left"></td></tr>
                                        </form>
                                    </table>


                                    <?
                                                       }
                                                   }
                                    ?>

                                </div>
                            </div>

                            <div id="footer">
                                <p><a href="http://www.web-air.co.uk" title="Copyrighted">v1.0.1 &copy; WebAir 2010</a></p>

                            </div>
                        </div>
                    </div>
                    <p style="text-align: center; font-size: 12px;">
                        <a href="privacy.php">Privacy</a> |
                        <a href="sitemap.php">Sitemap</a> |
                        <a href="contact.php">Contact Us</a>
                    </p>
                    <p style="text-align: right; width: 68em;">
                        <a href="http://validator.w3.org/check?uri=referer"><img
                                src="http://www.w3.org/Icons/valid-xhtml10"
                                alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>
                        <a href="http://jigsaw.w3.org/css-validator/check/referer">
                            <img style="border:0;width:88px;height:31px"
                                 src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
                                 alt="Valid CSS!" />
                        </a>
                    </p>
                </div>
            </div>
        </div>

    </body>

</html>
