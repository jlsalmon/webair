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

    <title>WebAir | Home</title>

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

  </head>

  <body onload="initDynamicOptionLists();">

    <div id="window">
      <div id="container">

        <div id="main-head">
          <a href="http://www.web-air.co.uk/index.php" title="WebAir Homepage">
            <img alt="webair logo"  src="layout/images/webair-logo-small.png"/>
          </a>
          <table class="smalltext" style="font-size:10px; vertical-align:top; clear:both; float:right;">
            <tr>
              <td valign="top"><?php
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
                <form action="process.php" method="post" style="float:right;"><?
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
              <td><?php echo $form->error("user"); ?><br /><? echo $form->error("pass"); ?></td>
            </tr><?php } ?>
          </table>
        </div>
      </div>

      <div id="navigation">
        <ul>
          <!-- The link you call "active" will show up as a darker tab -->
          <!--[if IE 6]><li></li><![endif]-->
          <li class="active"><span>Home</span></li>
          <li><a href="main.php">My Bookings</a></li>
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
              <h1 id="title"><a href=""></a></h1>
            </div>
            <table class="statistics">
              <tr>
                <td>
                  You are here: WebAir/<a href="index.php">Home Page</a>
                </td>
                <td></td>
                <td align="right" valign="top">
                  <a href="index.php" title="Home">
                    <img alt="Home" src="layout/images/home_icon_small.png" style="width: 2.5em; height: 2.5em;" /></a>
                  <a href="userinfo.php?user=<? echo $session->username; ?>" title="My Account">
                    <img alt="Home" src="layout/images/acct_icon_small.png" style="width: 2.5em; height: 2.5em;" /></a>
                  <a href="main.php" title="My Bookings">
                    <img alt="Home" src="layout/images/cart_icon_small.png" style="width: 2.5em; height: 2.5em;" /></a>
                </td>
              </tr>
              <tr>
                <td valign="top" colspan="2" rowspan="3">
                  <div class="contentbox" style="background-color:#e5e5e5; width:30em">

                    <img alt="family photo" src="layout/images/fam.jpg" class="photo" style="width: 10em; height: 10em;"/>
                    <p>Welcome to web-air.co.uk! We offer low cost, no-frills flights to many destinations
                      in the UK. We aim to make travelling by air as simple and hassle-free as possible.
                    </p>
                    <p>We hope you find using our website enjoyable. If you have any comments or queries,
                      please email us at: <strong><a href="info@web-air.co.uk">info@web-air.co.uk</a></strong>
                    </p>
                    <img alt="important" src="layout/images/emblem-important.png" />
                    <p><b>Remember, kids fly free!</b> Under 16's only, terms and conditions apply.
                    </p>
                  </div>
                  <div class="contents" style="background-color:#e5e5e5; width:30em">
                    <p>WebAir operates a single 50 seat aircraft from Bristol International Airport to the
                      following British destinations: Manchester, Dublin, Newcastle and Glasgow. <br /><br />
                      All tickets are economy class and hence the same price.
                    </p>
                    <img alt="plane photo"  class="centered-photo" src="layout/images/airliner.jpg"
                         style="width:25em; height: 13.45em;"/>
                    <p>Flights operate Monday to Saturday only.<br />
                      For timetables and fares, please click <a href="info.php">here.</a>
                    </p>
                  </div>
                </td>
                <td valign="top" rowspan="3">
                  <div class="contact" style="background-color: #3B5998; color: white;">
                    <br />
                    <span><b>> Book a flight</b></span>

                    <form id="booking" method="post" action="process.php">
                      <table>
                        <tr>
                          <td><span>From:</span></td>
                          <td>
                            <select name="origin" size="1" >
                              <option value="default"
                              <?php
                              if (!($form->value) || (!isset($_SESSION['flightorigin'])))
                                echo ("selected=\"selected\"");
                              ?>>
                              </option>
                              <option value="Bristol" <?php
                                      if (($form->value('origin') == 'Bristol') || ($_SESSION['flightorigin']) == 'Bristol')
                                        echo ("selected=\"selected\"");
                              ?>>Bristol</option>
                              <option value="Dublin" <?php
                                      if (($form->value('origin') == 'Dublin') || ($_SESSION['flightorigin']) == 'Dublin')
                                        echo ("selected=\"selected\"");
                              ?>>Dublin</option>
                              <option value="Glasgow" <?php
                                      if (($form->value('origin') == 'Glasgow') || ($_SESSION['flightorigin']) == 'Glasgow')
                                        echo ("selected=\"selected\"");
                              ?>>Glasgow</option>
                              <option value="Manchester" <?php
                                      if (($form->value('origin') == 'Manchester') || ($_SESSION['flightorigin']) == 'Manchester')
                                        echo ("selected=\"selected\"");
                              ?>>Manchester</option>
                              <option value="Newcastle" <?php
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
                                        echo '<script type="text/javascript">dest.forValue("'
                                        . $_SESSION['flightorigin']
                                        . '").setDefaultOptions("'
                                        . $_SESSION['flightdest']
                                        . '");</script>';
                                      } else if ($form->value('destination')) {
                                        echo '<script type="text/javascript">dest.forValue("'
                                        . $form->value('origin') . '").setDefaultOptions("'
                                        . $form->value('destination') . '");</script>';
                                      }
                              ?></select>
                                  </td>
                                </tr>
                              </table>
                              <span><br />Departing on:</span>
                              <input class="departure_date" maxlength="10" name="departure_date" readonly="readonly" id="departure_date" value="<?php
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
                                        echo "0"; ?>" selected="selected"><?php
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
                                </form><?php
                                      if ($form->num_errors > 0) {
                                        echo $form->error("origin") . "<br />";
                                        echo $form->error("destination");
                                        echo $form->error("departure_date") . "<br />";
                                        echo $form->error("num_adults");
                                      }?>

                                    </div>
                                    <div class="contact" style="background-color:#cacaca;">
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
                                        echo $form->value('username'); ?>"/>
                           </td>
                         </tr>
                         <tr>
                           <td>
                             <span>Booking Ref:</span>
                           </td>
                           <td>
                             <input type="text" name="bookingref" maxlength="6" value="<?php echo $form->value('bookingref'); ?>"/>
                           </td>
                         </tr>
                         <tr>
                           <td colspan="2" align="right">
                             <input type="hidden" name="sublookup" value="1" />
                             <input type="submit" value="Go" />
                           </td>
                         </tr>
                         <tr>
                           <td colspan="2"><? echo $form->error("user"); ?></td>
                         </tr>
                         <tr>
                           <td colspan="2"><? echo $form->error("bookingref"); ?></td>
                        </tr>
                      </form>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
            <table class="statistics" style="width: 62em;">
              <tr>
                <td colspan="3">
                  <p style="font-size: larger;">
                    WebAir flies to many popular destinations.
                    Here are some links that may be of interest when you arrive at your destination.</p>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="http://www.seeglasgow.com">
                    <img alt="family photo" src="layout/images/glasgow.png" class="photo" style="width: 15em; height: 10em;"/></a>
                </td>
                <td>
                  <a href="http://www.visitmanchester.com">
                    <img alt="family photo" src="layout/images/manchester.png" class="photo" style="width: 15em; height: 10em;"/></a>
                </td>
                <td>
                  <a href="http://www.newcastlegateshead.com">
                    <img alt="family photo" src="layout/images/newcastle.png" class="photo" style="width: 15em; height: 10em;"/></a>
                </td>
              </tr>
              <tr>
                <td><br /></td>
              </tr>
            </table>
            <div id="footer">
              <p><a href="http://www.web-air.co.uk/index.php" title="Copyrighted">v1.0.1 &copy; WebAir 2010</a></p>
            </div>
          </div>
        </div>
        <p style="text-align: center; font-size: 12px;">
          <a href="privacy.php">Privacy</a> |
          <a href="sitemap.php">Sitemap</a> |
          <a href="contact.php">Contact Us</a>
        </p>
        <p style="text-align: right; width: 68em;">
          <a href="http://validator.w3.org/check?uri=referer">
            <img src="http://www.w3.org/Icons/valid-xhtml10"
                 alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>
          <a href="http://jigsaw.w3.org/css-validator/check/referer">
            <img style="border:0;width:88px;height:31px"
                 src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
                 alt="Valid CSS!" />
          </a>
        </p>
      </div>
    </div>
  </body>
</html>