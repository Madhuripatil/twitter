<?php
session_start();
require_once('lib/twitteroauth/twitteroauth.php');
require_once('config.php');

$screen_name=$_REQUEST['screen_name'];

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$home_timeline = $connection->get('statuses/user_timeline' , array('screen_name' =>$screen_name, 'count'=>10 ,'include_rts'=>1));
 if(!empty($home_timeline)){
	 $html="";
	  foreach($home_timeline as $item){
           $html .='<li> <img class="profile_pic" src="'.$item->user->profile_image_url.'" style="padding:2px; " /> '. $item->user->name.'<br/><strong>'.$item->text.'</strong></li>';
	  }
 }else
 {
		 $html .= $screen_name ." has not tweeted yet or you dont have permission to view  this profile";
 }
 echo $html;