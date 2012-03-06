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

    <title>WebAir | My Bookings</title>

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
    <!-- Date picker stuff -->
    <link rel="stylesheet" media="screen" type="text/css" href="./datepicker/css/datepicker.css" />
    <script type="text/javascript" src="./datepicker/js/jquery.js"></script>
    <script type="text/javascript" src="./datepicker/js/datepicker.js"></script>
    <script type="text/javascript" src="./datepicker/js/eye.js"></script>
    <script type="text/javascript" src="./datepicker/js/utils.js"></script>
    <script type="text/javascript" src="./datepicker/js/layout.js"></script>

    <!-- validation stuff -->
    <script src="./include/gen_validatorv31.js" type="text/javascript"></script>
    <script type="text/javascript" src="./include/custom_validations.js"></script>

    <!-- Dynamic drop down box stuff -->
    <script src="./include/DynamicOptionList.js" type="text/javascript"></script>
    <script type="text/javascript" src="./include/initDynamicOptionList.js"></script>
    <script src="./include/collapse.js" type="text/javascript"></script>
  </head>
  
  <body onload="initDynamicOptionLists();">
    <div id="window">
      <div id="container">
        <div id="main-head">
          <a href="index.php" title="WebAir Homepage"><img alt="webair logo"  src="layout/images/webair-logo-small.png"/></a>
          <table class="smalltext" style="font-size:10px; vertical-align:top; clear:both; float:right;">
            <tr>
              <td valign="top"><?
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
?><form action="process.php" method="post" style="float:right;"><?
                /**
                 * User not logged in, display the login form.
                 * If user has already tried to login, but errors were
                 * found, display the total number of errors.
                 * If errors occurred, they will be displayed.
                 */
                if ($form->num_errors > 0) {
                  echo "<span><font color=\"#ff0000\">" . $form->num_errors . " error(s) found</font></span>";
                }
?><span>Login: </span>
                  <input type="text" size="5" class="smalltext" name="user" maxlength="30" value="Username" onfocus="this.value='';" />
                  <input type="password" size="5" class="smalltext" name="pass" maxlength="30" value="Password" onfocus="this.value='';"/>
                  <input type="checkbox" name="remember" class="smalltext"<?
                  if ($form->value("remember") != "") {
                    echo "checked";
                  }
?>/>
                  <span>Remember me</span>
                  <input type="hidden" name="sublogin" value="1" />
                  <input type="submit" value="Login" style="height: 1.7em; width: 4em; background-color:#3B5998; font-size:8pt; color:white;" />

                </form>
              </td>
            </tr>
            <tr>
              <td><? echo $form->error("user"); ?><br /><? echo $form->error("pass"); ?></td>
            </tr><?
                       }
?></table>
                   </div>
                   <div id="navigation">
                     <ul>
                       <!-- The link you call "active" will show up as a darker tab -->
                       <!--[if IE 6]><li></li><![endif]-->
                       <li><a href="index.php">Home</a></li>
                       <li class="active"><span>My Bookings</span></li>
                       <li><a href="userinfo.php?user=<?php echo $session->username; ?>">My Account</a></li>
                       <li><a href="info.php">Travel Information</a></li>
                       <li><a href="register.php">Register</a></li>
                       <li><a href="about.php">About</a></li>
                     </ul>
                   </div>
                   <div id="main">
                     <div id="outer-prettification">
                       <div id="inner-prettification">
                         <div id="header">
                           <h1 id="title"><a href="main.php"> </a></h1>
                         </div>
                         <div id="contents">
                           <div class="comment" style="display: run-in; background-color:#3B5998; color:white;"><?
                       /**
                        * User has already logged in, so display relevant
                        * information and submitted flight details.
                        */
                       if ($session->logged_in) {

                         echo "Welcome <b>$session->username</b>, you are logged in.&nbsp;&nbsp;"
                         . "[<a href=\"userinfo.php?user=$session->username\" style=\"color:white;\">My Account</a>] &nbsp;&nbsp;";
                         if ($session->isAdmin()) {
                           echo "[<a href=\"admin/admin.php\">Admin Center</a>] &nbsp;&nbsp;";
                         }
                         echo "[<a href=\"process.php\" style=\"color:white;\">Logout</a>]";

                         if (isset($_SESSION['prebook'])) {
?><div class="contentbox" style="width: auto; text-align: left; font-size: larger;">
                             <div class="contentbox" style="background-color: #e5e5e5; width: 40em;">
                               <img alt="information"  src="/layout/images/info.png" />
                               <h2>Your Flight Details</h2>
                               <table class="flat-lined" style="width:inherit; background-color: #fafafa;">
                                 <tr>
                                   <td>From:</td>
                                   <td><b><?php echo $_SESSION['flightorigin']; ?></b></td>
                                 </tr>
                                 <tr>
                                   <td>To:</td>
                                   <td><b><?php echo $_SESSION['flightdest']; ?></b></td>
                                 </tr>
                                 <tr>
                                   <td>Departing On:</td>
                                   <td><b><?php echo $_SESSION['flightdepdate']; ?></b></td>
                                 </tr>
                                 <tr>
                                   <td>Departure Time:</td>
                                   <td><b><?php echo $_SESSION['flightdeptime']; ?></b></td>
                                 </tr
                                 <tr>
                                   <td>Arrival Time:</td>
                                   <td><b><?php echo $_SESSION['flightarrtime']; ?></b></td>
                                 </tr>
                                 <tr>
                                   <td>Adults:</td>
                                   <td><b><?php echo $_SESSION['flightnumadult']; ?></b></td>
                                 </tr>
                                 <tr>
                                   <td>Children:</td>
                                   <td><b><?php echo $_SESSION['flightnumchild']; ?></b></td>
                                 </tr>
                                 <a href="<?php unset($_SESSION['prebook']); ?>main.php">-> <u>Edit Flight</u></a>
                               </table>
                               <p style="font-size: smaller;">You'll confirm your booking in the next step.</p>
                               <form action="process.php" method="post">
                                 <input type="hidden" name="subbook" value="1" />
                                 <input type="submit" value="-> Continue"/>
                               </form>
                             </div>
                           </div><?
                           /**
                            * User is logged in but has not submitted
                            * booking form yet.
                            */
                         } else {
?><div class="contentbox" style="width: auto; text-align: left;">
                             <table class="statistics" style="border: none;">
                               <tr>
                                 <td>
                                   <div class="contentbox" style="background-color: #e5e5e5;">
                                     <br />
                                     <span><b>> Book a flight</b></span>

                                     <form id="booking" method="post" action="process.php">
                                       <table>
                                         <tr>
                                           <td><span>From:</span></td>
                                           <td>
                                             <select name="origin" size="1" >
                                               <option value="default"<?php
                           if (!($form->value) || (!isset($_SESSION['flightorigin'])))
                             echo ("selected=\"selected\"");
?>></option>
                                      <option value="Bristol"<?php
                                              if (($form->value('origin') == 'Bristol') || ($_SESSION['flightorigin']) == 'Bristol')
                                                echo ("selected=\"selected\"");
?>>Bristol</option>
                                      <option value="Dublin"<?php
                                              if (($form->value('origin') == 'Dublin') || ($_SESSION['flightorigin']) == 'Dublin')
                                                echo ("selected=\"selected\"");
?>>Dublin</option>
                                      <option value="Glasgow"<?php
                                              if (($form->value('origin') == 'Glasgow') || ($_SESSION['flightorigin']) == 'Glasgow')
                                                echo ("selected=\"selected\"");
?>>Glasgow</option>
                                      <option value="Manchester"<?php
                                              if (($form->value('origin') == 'Manchester') || ($_SESSION['flightorigin']) == 'Manchester')
                                                echo ("selected=\"selected\"");
?>>Manchester</option>
                                      <option value="Newcastle"<?php
                                              if (($form->value('origin') == 'Newcastle') || ($_SESSION['flightorigin']) == 'Newcastle')
                                                echo ("selected=\"selected\"");
?>>Newcastle</option>
                                    </select>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <span>To:</span>
                                  </td>
                                  <td>
                                    <select name="destination"><?php
                                              if (isset($_SESSION['flightdest'])) {
                                                echo '<script type="text/javascript">dest.forValue("' . $_SESSION['flightorigin'] . '").setDefaultOptions("' . $_SESSION['flightdest'] . '");</script>';
                                              } else if ($form->value('destination')) {
                                                echo '<script type="text/javascript">dest.forValue("' . $form->value('origin') . '").setDefaultOptions("' . $form->value('destination') . '");</script>';
                                              }
?></select>
                                          </td>
                                        </tr>
                                      </table>

                                      <span><br />Departing on:</span>
                                      <input class="departure_date" name="departure_date" readonly="readonly" id="departure_date" value="<?php
                                              if ($form->value('departure_date')) {
                                                echo $form->value('departure_date');
                                              } else if (isset($_SESSION['flightdepdate'])) {
                                                echo $_SESSION['flightdepdate'];
                                              }
                                              else
                                                echo(date("Y-m-d"));
?>"/>

                                       <script type="text/javascript" >

                                         $('#departure_date').DatePicker({
                                           format:'Y-m-d',
                                           date: $('#departure_date').val(),
                                           current: $('#departure_date').val(),
                                           starts: 1,
                                           position: 'right',
                                           onBeforeShow: function(){
                                             $('#departure_date').DatePickerSetDate($('#departure_date').val(), true);
                                           },
                                           onChange: function(formated, dates){
                                             $('#departure_date').val(formated);
                                             if ($('#closeOnSelect input').attr('checked')) {
                                               $('#departure_date').DatePickerHide();
                                             }
                                           }
                                         });
                                       </script>

                                       <span><br /><br />Passengers:</span><br />

                                       <select name="num_adults">
                                         <option value="<?php
                                              if ($form->value("num_adults")) {
                                                echo $form->value('num_adults');
                                              } else if (isset($_SESSION['flightnumadult'])) {
                                                echo $_SESSION['flightnumadult'];
                                              }
                                              else
                                                echo "0";
?>" selected="selected"><?php
                                              if ($form->value("num_adults")) {
                                                echo $form->value("num_adults");
                                              } else if (isset($_SESSION['flightnumadult'])) {
                                                echo $_SESSION['flightnumadult'];
                                              }
                                              else
                                                echo "Adults";
?></option>

                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                          </select>
                                          <select name="num_children">
                                            <option value="<?php
                                              if ($form->value("num_children")) {
                                                echo $form->value('num_children');
                                              } else if (isset($_SESSION['flightnumchild'])) {
                                                echo $_SESSION['flightnumchild'];
                                              }
                                              else
                                                echo "0"; ?>" selected="selected"><?php
                                              if ($form->value("num_children")) {
                                                echo $form->value("num_children");
                                              } else if (isset($_SESSION['flightnumchild'])) {
                                                echo $_SESSION['flightnumchild'];
                                              }
                                              else
                                                echo "Children";
?></option>
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                          </select><br /><br />

                                          <input type="hidden" name="subprebook" value="1" />
                                          <input type="submit" value="Show Flights"></input>
                                          <br /><br />
                                        </form><?
                                              if ($form->num_errors > 0) {
                                                echo $form->error("origin") . "<br />";
                                                echo $form->error("destination");
                                                echo $form->error("departure_date") . "<br />";
                                                echo $form->error("num_adults");
                                              }
?></div>
                                          </td>
                                          <td align="right" valign="top">
                                            <div class="contentbox" style="background-color:#e5e5e5;">

                                              <img alt="information" align="right" src="layout/images/info.png" />
                                              <br /><b>> Manage Bookings</b>

                                              <table style="margin:0;">
                                                <form action="process.php" method="post">
                                                  <tr>
                                                    <td>Username:</td>
                                                    <td><input type="text" name="user" maxlength="30" value="<?php
                                              if ($session->username != "Guest") {
                                                echo $session->username;
                                              }
                                              else
                                                echo $form->value('username');
?>" /></td></tr>
                                 <tr><td>Booking Ref:</td>
                                   <td><input type="text" name="bookingref" maxlength="6" value="<?php echo $form->value('bookingref'); ?>"/></td></tr>
                                 <tr><td colspan="2" align="right">
                                     <input type="hidden" name="sublookup" value="1" />
                                     <input type="submit" value="Go" /></td></tr>
                                 <tr><td colspan="2"><? echo $form->error("user"); ?></td></tr>
                                 <tr><td colspan="2"><? echo $form->error("bookingref"); ?></td></tr>
                               </form>
                             </table>
                           </div>
                         </td>
                       </tr>
                       <tr>
                         <td colspan="2">
                           <div class="contentbox" style="width:auto;">
                             <img alt="important"  src="layout/images/emblem-important.png" />
                             <span>
                               Please remember that WebAir operates from Monday to Saturday only.
                               Please also remember that flights can only be booked a maximum of 3 months in advance.
                             </span>
                           </div>
                         </td>
                       </tr>
                     </table>
                   </div><?
                                            }
                                          } else {
                                            /**
                                             * User not logged in, display the login form.
                                             * If user has already tried to login, but errors were
                                             * found, display the total number of errors.
                                             * If errors occurred, they will be displayed.
                                             */
?><div class="contents" style="width: auto;">
                                              <span>Welcome Guest, please log in or register to continue.</span>
                                              <div class="contentbox" style="width:auto; background-color: #e5e5e5">
                                                <a href="javascript:;" onmousedown="swapImage(getElementById('img2')); toggleSlide('mydiv');">
                                                  <img alt="arrow"  id="img2" name="img2" src="./layout/images/arrow-right.gif" style="float: left;"/>
                                                  &nbsp; Got An Account Already? Click Here!</a>
                                                <div id="mydiv" style="display:none; overflow:hidden; height:100%; width:50em;">
                                                  <table style="color:black; width:auto">

                                                    <form action="process.php" method="POST">

                                                      <tr>
                                                        <td><h3>Log In</h3></td>
                                                        <td></td>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

                                                      </tr>
                                                      <tr>
                                                        <td>Username:</td>
                                                        <td><input type="text" name="user" maxlength="30" value="<? echo $form->value("user"); ?>" /></td>

                                                      </tr>
                                                      <tr>
                                                        <td>Password:</td>
                                                        <td><input type="password" name="pass" maxlength="30" value="<? echo $form->value("pass"); ?>" /></td>

                                                      </tr>
                                                      <tr>
                                                        <td><input type="checkbox" name="remember"<?
                                            if ($form->value("remember") != "") {
                                              echo "checked";
                                            }
?>/>
                                   Remember me</td>
                                 <td align="right"><input type="hidden" name="sublogin" value="1" />
                                   <input type="submit" value="Login" /></td>

                               </tr>
                               <tr>
                                 <td>[<a href="forgotpass.php">Forgot Password?</a>]</td>
                                 <td><?
                                            if ($form->num_errors > 0) {
                                              echo "<font size=\"2\" color=\"#ff0000\">" . $form->num_errors . " error(s) found</font>";
                                            }
?></td>

                                        </tr>
                                        <tr>
                                          <td></td>
                                          <td><? echo $form->error("user"); ?></td>
                                        </tr>
                                        <tr>
                                          <td></td><td><? echo $form->error("pass"); ?></td>
                                        </tr>

                                      </form>
                                    </table>
                                  </div></div><?
                                          }
                                          if (!$session->logged_in) {
                                            /**
                                             * The user has submitted the registration form and the
                                             * results have been processed.
                                             */ if (isset($_SESSION['regsuccess'])) {
                                              /* Registration was successful */
                                              if ($_SESSION['regsuccess']) {
                                                echo "<tr><td><h2>Registered!</h2></tr></td>";
                                                echo "<tr><td colspan='3'>Thank you <b>" . $_SESSION['reguname'] . "</b>, your information has been added to the database. "
                                                . "<br />You may now <a href=\"main.php\">log in</a>.</tr></td></table></div>";
                                              }
                                              /* Registration failed */ else {
                                                echo "<h1>Registration Failed</h1>";
                                                echo "<p>We're sorry, but an error has occurred and your registration for the username <b>" . $_SESSION['reguname'] . "</b>, "
                                                . "could not be completed.<br>Please try again at a later time.</p>";
                                              }
                                              unset($_SESSION['regsuccess']);
                                              unset($_SESSION['reguname']);
                                            }
                                            /**
                                             * The user has not filled out the registration form yet.
                                             * Below is the page with the sign-up form, the names
                                             * of the input fields are important and should not
                                             * be changed.
                                             */ else {
?><table>
                                              <form action="process.php" method="POST">
                                                <tr>
                                                  <td colspan="20"><hr /></td>
                                                  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                  <td rowspan="10" valign="top" align="right">
                                                    <div class="contentbox" style="">
                                                      <span>Please use the forms opposite to log in or register.
                                                        You must be registered and logged in to book a flight
                                                        with WebAir.</span><br /><br />
                                                      <span style="font-size: smaller;">
                                                        > Usernames must be 5 characters or greater, and must
                                                        only contain alphanumeric
                                                        characters.<br /><br />
                                                        > Passwords must be 5 characters or greater.<br />
                                                      </span>
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td><h3>Not registered? Join Here!</h3></td>

                                                </tr>
                                                <tr>
                                                  <td>First Name:</td>
                                                  <td><input type="text" name="fname" maxlength="30" value="<? echo $form->value("fname"); ?>" /></td>
                                                </tr>
                                                <tr>
                                                  <td>Last Name:</td>
                                                  <td><input type="text" name="lname" maxlength="30" value="<? echo $form->value("lname"); ?>" /></td>

                                                </tr>
                                                <tr>
                                                  <td>Choose Username:</td>
                                                  <td><input type="text" name="user" maxlength="30" value="<? echo $form->value("user"); ?>" /></td>
                                                </tr>
                                                <tr>
                                                  <td>Choose Password:</td>
                                                  <td><input type="password" name="pass" maxlength="30"/></td>
                                                </tr>
                                                <tr>
                                                  <td>Confirm Password:</td>
                                                  <td><input type="password" name="passconf" maxlength="30"/></td>
                                                </tr>
                                                <tr>
                                                  <td>Email:</td>
                                                  <td><input type="text" name="email" maxlength="50" value="<? echo $form->value("email"); ?>" /></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2" align="right">
                                                    <input type="hidden" name="subjoin" value="1" />
                                                    <input type="submit" value="Join!" /></td>
                                                </tr>
                                                <tr>
                                                  <td><?
                                              if ($form->num_errors > 0) {
                                                echo "<td><font size=\"2\" color=\"#ff0000\">" . $form->num_errors . " error(s) found</font></td>";
                                              }
?></td>
                                          </tr>
                                          <tr>
                                            <td></td>
                                            <td><? echo $form->error("user"); ?></td>
                                          </tr>
                                          <tr>
                                            <td></td>
                                            <td><? echo $form->error("passconf"); ?></td>
                                          </tr>
                                          <tr>
                                            <td></td>
                                            <td><? echo $form->error("email"); ?></td>
                                          </tr>
                                          <tr>
                                            <td></td>
                                            <td><? echo $form->error("fname"); ?></td>
                                          </tr>
                                          <tr>
                                            <td></td>
                                            <td><? echo $form->error("lname"); ?></td>
                                          </tr>

                                        </form>
                                      </table>
                                    </div><?
                                            }
                                          }
?></div>
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
