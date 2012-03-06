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

    <title>WebAir | Information</title>

    <style type="text/css" title="simplicity" media="all">
      @import "layout/simplicity-style.css";
    </style>
    <!--[if IE]>
    <style type="text/css" media="all">
      @import "layout/ie-diff.css";
    </style>
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="layout/print.css" media="print" />
    <script src="./include/collapse.js" type="text/javascript"></script>
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
                  <input type="submit" value="Login" style="height: 1.7em; width: 4em; background-color:#3B5998
                         ; font-size:8pt; color:white;" />

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
                <li><a href="userinfo.php?user=<?php echo $session->username; ?>">My Account</a></li>
            <li class="active"><span>Travel Information</span></li>
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

              <div id="contents">

                <div class="comment" style="background-color: #3B5998; color:white;">
                  <div class="contents" style="width: auto; color: black;">
                    <img alt="information"  src="layout/images/info.png" />
                    Please click on one of the links below to view information on the topic you require.
                    <div class="contentbox" style="width:auto;">
                      <a href="javascript:;" onmousedown="swapImage(getElementById('img')); toggleSlide('mydiv');">
                        <img alt="arrow"  id="img" name="img" src="./layout/images/arrow-right.gif" style="float: left;"/>&nbsp; Timetables</a>
                      <div id="mydiv" style="display:none; overflow:hidden; height:100%; width:50em;">
                        <p>
                          <h3><b>Timetable</b></h3>
                          <ul>
                            <li>WebAir operates a single 50 seat aircraft from Bristol International Airport to the
                              following British destinations: Manchester, Dublin, Newcastle and Glasgow.</li>
                            <li>Flights operate Monday to Saturday only. Flights can only be booked up to 3 months in advance.
                            </li>
                          </ul>
                        </p>
                        <p>The timetable is as follows:</p>

                        <table class="statistics" style="border:1px; color:black" border="1">
                          <tr>
                            <th>Leave</th>	<th>At</th>     <th>Arrive</th>	<th>At</th> <th>Fare</th>
                          </tr>
                          <tr>
                            <td>Bristol</td>
                            <td>07:00</td>
                            <td>Newcastle</td>
                            <td>08:15</td>
                            <td>£55</td>
                          </tr>
                          <tr>
                            <td>Newcastle</td>
                            <td>08:45</td>
                            <td>Bristol</td>
                            <td>10:00</td>
                            <td>£55</td>
                          </tr>
                          <tr>
                            <td>Bristol</td>
                            <td>10:40</td>
                            <td>Manchester</td>
                            <td>11:45</td>
                            <td>£40</td>
                          </tr>
                          <tr>
                            <td>Manchester</td>
                            <td>12:20</td>
                            <td>Bristol</td>
                            <td>13:00</td>
                            <td>£40</td>
                          </tr>
                          <tr>
                            <td>Bristol</td>
                            <td>13:25</td>
                            <td>Dublin</td>
                            <td>14:00</td>
                            <td>£45</td>
                          </tr>
                          <tr>
                            <td>Dublin</td>
                            <td>14:25</td>
                            <td>Glasgow</td>
                            <td>15:10</td>
                            <td>£35</td>
                          </tr>
                          <tr>
                            <td>Glasgow</td>
                            <td>15:40</td>
                            <td>Bristol</td>
                            <td>17:00</td>
                            <td>£65</td>
                          </tr>
                          <tr>
                            <td>Bristol</td>
                            <td>17:40</td>
                            <td>Glasgow</td>
                            <td>19:00</td>
                            <td>£65</td>
                          </tr>
                          <tr>
                            <td>Glasgow</td>
                            <td>19:30</td>
                            <td>Newcastle</td>
                            <td>20:05</td>
                            <td>£35</td>
                          </tr>
                          <tr>
                            <td>Newcastle</td>
                            <td>20:30</td>
                            <td>Manchester</td>
                            <td>21:05</td>
                            <td>£35</td>
                          </tr>
                          <tr>
                            <td>Manchester</td>
                            <td>21:40</td>
                            <td>Bristol</td>
                            <td>22:40</td>
                            <td>£40</td>
                          </tr>

                        </table>
                      </div>
                    </div>
                    <div class="contentbox" style="width:auto;">
                      <a href="javascript:;" onmousedown="swapImage(getElementById('img2')); toggleSlide('mydiv2');">
                        <img alt="arrow"  id="img2" name="img2" src="./layout/images/arrow-right.gif" style="float: left;"/>&nbsp; Check-In Information</a>
                      <div id="mydiv2" style="display:none; overflow:hidden; height:100%; width:50em;">
                        <span>
                          <h3><b>Check-In Information</b> <small>(Applies to all airports that WebAir flies from)</small></h3>
                          <p>
                            You must pass through check-in no later than 45 minutes before your
                            flight and ensure that you have entered security at least 35 minutes
                            before your flight departs. If you arrive at check in later than 45
                            minutes and/or you enter security later than 35 minutes before your flight
                            departs, you will not be allowed to board your flight.</p>
                        </span>
                      </div>
                    </div>
                    <div class="contentbox" style="width:auto;">
                      <a href="javascript:;" onmousedown="swapImage(getElementById('img3')); toggleSlide('mydiv3');">
                        <img alt="arrow"  id="img3" name="img3" src="./layout/images/arrow-right.gif" style="float: left;"/>&nbsp; Baggage Information</a>
                      <div id="mydiv3" style="display:none; overflow:hidden; height:100%; width:50em;">
                        <span>

                          <h3>Baggage Allowance Guidelines</h3>
                          <ul>
                            <li>1 Bag per passenger, each bag a maximum 50 lbs (23 kg) and 62 linear inches (157 cm) (total length + width + height.) </li>
                            <li>If your baggage exceeds these requirements above, then an additional charge will be incurred. Charges vary all depending
                              on the excess. For additional information please refer to airport information.
                            </li>
                          </ul>
                          <br></br>
                          <h3> Tips for checking your baggage in for a flight</h3>
                          <ul>
                            <li> We advise that all baggage being checked should be labelled with your name and address on the outside bag as well as the
                              inside. Nametags are available at all airport baggage check-in locations.
                            </li>
                            <li>When checking in, please make sure you inform us of your final destination if you have connected flight so that all your
                              baggage will be checked to your final destination.
                            </li>
                            <li>Please make sure that you claim all your baggage upon arrival of your destination immediately.
                            </li>
                            <li>Be aware of what items are restricted to be in checked baggage. If you are not sure, review the list of items permitted
                              and prohibited shown on the <a href="http://www.dft.gov.uk"><b>Department For Transport Website.</b></a>
                            </li>
                            <li>Allow enough time for your checked baggage to be accepted.
                            </li>
                          </ul>
                          <br></br>
                          <h3> Personal hand baggage</h3>
                          <b>ONE piece of hand baggage per passenger for all flights.</b>
                          <br></br>
                          <dl>
                            <dt>The following allowances apply for all flights:</dt>
                          </dl>
                          <ul>
                            <li>One standard-sized bag - maximum size of the bag must not exceed 56x45x25cm (22x17.5x9.85in)
                            </li>
                            <li>The hand baggage must not exceed 23kg (51lbs) and you must be able to lift the bag into the overhead
                              lockers in the aircraft independently.
                            </li>
                          </ul>
                          <dl>
                            <dt>Please note there is restrictions on liquid in hand baggage:</dt>
                          </dl>
                          <ul>
                            <li>All liquids should be in a container that do not exceed a volume of 100ml.
                            </li>
                            <li>All liquid containers meeting the maximum volume of 100ml should be fitted comfortably into a
                              transparent, re-sealable 1 litre plastic bag (20cm x 20cm)
                            </li>
                            <li>The plastic bag should be shown separately at security.
                            </li>
                          </ul>
                        </span>
                      </div>
                    </div>
                    <div class="contentbox" style="width:auto;">
                      <a href="javascript:;" onmousedown="swapImage(getElementById('img4')); toggleSlide('mydiv4');">
                        <img alt="arrow"  id="img4" name="img4" src="./layout/images/arrow-right.gif" style="float: left;"/>&nbsp; Travel Documentation Requirements</a>
                      <div id="mydiv4" style="display:none; overflow:hidden; height:100%; width:50em;">
                        <h3>Travel Requirements When Travelling Within The UK </h3>
                        <h4>If you are travelling on a UK domestic flight. You will need appropriate ID present at check-in. We accept the following forms of ID on
                          domestic flights:</h4>
                        <ul>
                          <li> A Valid passport - an expired passport can be used up to a maximum of two years after expiry
                          </li>
                          <li>Valid photographic EU or Swiss national identity card
                          </li>
                          <li>Valid photographic driving licence
                          </li>
                          <li>Valid armed forces identity card
                          </li>
                          <li>Valid police warrant card/badge
                          </li>
                          <li>Valid airport employees security identity pass
                          </li>
                          <li>A child on parent' s passport is an acceptable form of ID
                          </li>
                          <li>CitizenCard
                          </li>
                          <li>Valid photographic firearm certificate
                          </li>
                          <li>Valid Government-issued identity card
                          </li>
                          <li>SMART card
                          </li>
                          <li> Electoral identity card
                          </li>
                          <li>Pension Book
                          </li>
                        </ul>
                        <h3>General Passport information</h3>
                        <ul>
                          <li>You must hold a valid passport (including day trips). If you find that your passport has expired/will expir before
                            flying then please apply for a renewed passport in plenty of time before you travel.
                          </li>
                          <li>The identity passport service has introduced changes for applying for a passport. All first time adults must
                            attend an interview before any further procedures are done with the application. This can take up to six weeks.
                            For further information please visit <a class="hyperlink2" href="http://www.ips.gov.uk"><b>www.ips.gov.uk</b></a>
                          </li>
                          <li>Check and ensure that your passport is valid for the duration of the trip.
                          </li>
                          <li>Make a note of your passport number and date of issue and keep it separately in a safe place.
                          </li>
                        </ul>
                        <h3>Passports for children</h3>
                        <p>All children, including newborn babies, who are not included on a valid British passport, require their own valid passport for travel abroad.
                        </p>
                        <h4>Children who were included on a passport Before 5 October 1998.
                        </h4>
                        <p>Children who are already included on a valid passport may continue to travel with the passport holder; however the adult passport holder
                          with children is to expiry this year. The child will have to apply for their own valid passport when this occurs.
                        </p>
                        <p>Children aged 16 or over must have their own individual passport, even if the childs 16th birthday is when he/she is abroad.
                        </p>
                        <p>For further information regarding passport requirements, visit Identity and Passport Services (IPS) website
                          <a href="http://www.ips.gov.uk"><b>www.ips.gov.uk</b></a>
                        </p>

                      </div>
                    </div>
                    <div class="contentbox" style="width: auto; text-align: center;">
                    <h2>> Where we fly</h2>
                    <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                            src="http://maps.google.com/maps/ms?ie=UTF8&amp;msa=0&amp;msid=214848771146395743674.00049d0ecfd2688c241b8&amp;ll=54.575311,-5.21875&amp;spn=1.331828,3.353516&amp;z=5&amp;output=embed">

                    </iframe><br />
                    <small>View <a href="http://maps.google.com/maps/ms?ie=UTF8&amp;msa=0&amp;msid=214848771146395743674.00049d0ecfd2688c241b8&amp;ll=55.875311,-4.2&amp;spn=17.331828,37.353516&amp;z=4&amp;source=embed"
                                   style="color:#0000FF;text-align:left">WebAir map</a> in a larger map</small>
                    </div>
                  </div>
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
