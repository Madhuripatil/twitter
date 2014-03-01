<?php

/**
 * @file
 * Check if consumer token is set and if so send user to get a request token.
 */

/**
 * Exit with an error message if the CONSUMER_KEY or CONSUMER_SECRET is not defined.
 */
require_once('config.php');
if (CONSUMER_KEY === '' || CONSUMER_SECRET === '' || CONSUMER_KEY === 'CONSUMER_KEY_HERE' || CONSUMER_SECRET === 'CONSUMER_SECRET_HERE') {
  echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://dev.twitter.com/apps">dev.twitter.com/apps</a>';
  exit;
}

/* Build an image link to start the redirect process. */
$content = '<a href="./redirect.php"><img src="./images/button-twitter.png" alt="Sign in with Twitter"/></a>';
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Twitter Application</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="css/index.css">   
  </head>
  <body>
  <center>
     <div id="tw_main_container" >
    <div >
   <div id="tw_title" >
      <h2>Welcome to a Twitter OAuth challange.</h2>     
      </div>
      <hr />    
    </div> 
    <p>
      <pre>
        <?php print_r($content); ?>
      </pre>
    </p>
    </div>
</center>
  </body>
</html>
