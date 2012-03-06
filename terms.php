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

    <title>WebAir | Terms and Conditions</title>

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
                <li><a href="userinfo.php?user=<?php echo $session->username; ?>">My Account</a></li>
                <li><a href="info.php">Travel Information</a></li>
                <li><a href="register.php">Register</a></li>
                <li  class="active"><span>About</span></li>
              </ul>
            </div>

            <div id="main">
              <div id="outer-prettification">
                <div id="inner-prettification">

                  <div id="header">
                    <h1 id="title"><a href="#"></a></h1>
                  </div>

                  <div id="contents">
                    <div class="comment" style="background-color: #3B5998; color:white;">
                      <div class="contentbox" style="width:auto;">
                        <p><h3>Terms And Conditions</h3>
                        We own your soul, blah blah blah</p>
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
<?php
