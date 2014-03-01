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

if($_POST)
{
$q=$_POST['search'];
$followers_id = $connection->get('followers/ids' , array('screen_name' => $screen_name));  // count doesnot work  

$followers_list = array_slice($followers_id->ids,0,10000);
$followers_details = implode(",",$followers_list);
$followers = $connection->get('users/lookup' , array('user_id' => $followers_details)); 


$names ="";
foreach($followers as $follower ){

	if (strrpos($follower->screen_name,$_POST['search']) !== False)
	{
		$names .= "<div style='width:140px'> <a href='javascript:void(0);' onclick= myfollower('".$follower->screen_name."','". $follower->id."'); > ".$follower->screen_name."</a></div>"; 
		
	}

}
print_r($names);

}
