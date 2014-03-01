<?php
/**
 * File Created By Madhuri Patil 
 * Title- Twitter assigment  
 */

/* Load required lib files. */
session_start();
require_once('lib/twitteroauth/twitteroauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('statuses/home_timeline', array('count' => 10));

$user = $connection->get('account/verify_credentials');
$name = $user->screen_name;

/* Get Followers Id*/
$followers_id = $connection->get('followers/ids' , array('screen_name' => $_SESSION['screen_name']));  // count doesnot work  
$followers_list = array_slice($followers_id->ids,0,10);
$followers_details = implode(",",$followers_list);
$followers = $connection->get('users/lookup' , array('user_id' => $followers_details)); 


?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Twitter Application</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="http://600social.com/tee_turtle/mp/twitter/lib/jquery.bxslider/jquery.bxslider.min.js"></script>
	<!-- bxSlider CSS file -->
	<link href="http://600social.com/tee_turtle/mp/twitter/lib/jquery.bxslider/jquery.bxslider.css" rel="stylesheet" />
	 <link rel="stylesheet" href="css/index.css">  	

  </head>
  <body>
	  
	<?php if (isset($status_text)) { ?>
	  <?php echo '<h3>'.$status_text.'</h3>'; ?>
	<?php }else{
	?>
	<center>
	<div id="page_content">
	<h1>Welcome <?php echo $user->screen_name; ?></h1>
	<div id="sections">
		<div style="width:1000px;"><h2>Your Tweets</h2>
		<!-- Slider -->
			<div id="wrapper_slider">
		
				<p>

				<?php 
				echo '<ul class="bxslider">';

				foreach($content as $item){
					echo '<li> <img class="profile_pic" src="'.$item->user->profile_image_url.'" style="padding:2px; " /> '. $item->user->name.'<br/><strong>'.$item->text.'</strong></li>'; 

					}

				echo '</ul>';

				?>

				</p>
			</div><!--End  Slider -->
		</div>
        <div>
        <!-- Download Tweets -->
            <form action="download.php" method="post">
        <select id="file_format" name="file_format">
          <option value="csv">csv</option>
          <option value="xls">xls</option>
          <option value="xml">xml</option>
          <option value="json">json</option>
          <option value="pdf">pdf</option>  
          <!--<option value="google-spreadhseet">google-spreadhseet</option> -->
        </select>
        <input type="submit" id="submit1" name="submit1" value="Download All Tweets"/>
        
        </form>
        </div>

	<div class="content">
	<input type="text" class="search" id="searchid" placeholder="Search for Followers" />
	<div id="result" style="background-color: gray; width: 169px;"></div>
	</div>

	<!-- Twitter Followers  -->
	<h3>Your Followers : <?php echo $_SESSION['screen_name']; ?></h3>
	<div style="width:300px; margin-left: 62px;"> <!-- Followers  start -->

		<?php 
		foreach($followers as $follower ){
		?>
		<a href='javascript:void(0);' onclick= "myfollower('<?php echo $follower->screen_name; ?>','<?php echo $follower->id; ?>');">    <img src="<?php echo $follower->profile_image_url; ?>" style="float:left; cursor:pointer;" hspace="5" vspace="5" title="<?php echo $follower->name."| ".$follower->screen_name; ?>"  /><?php //echo $follower->name;?> </a>
		<?php

		}
		?> 
	</div>


	</div>
	</center>


	<script language="JavaScript">
	//slider script
	var slider =  $('.bxslider').bxSlider({
	auto:true});

	// function to get followers recent tweet 
	function myfollower(screen_name,prof_id )
	{
		$.ajax({
			type:"POST",
			url:"getfollowers.php",
			data:"screen_name="+screen_name,
			success:function(data){
			$('.bxslider').empty();
			$('.bxslider').append(data);

			slider.reloadSlider();
			}
		});

	}
	
	$(function(){
		$(".search").keyup(function() 
		{ 
		var searchid = $(this).val();
		var dataString = 'search='+ searchid+'&screen_name=<?php echo $name; ?>' ;
		if(searchid!='')
		{
			$.ajax({
			type: "POST",
			url: "search.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$("#result").html(html).show();
			}
			});
		}return false;    
		});
		
		jQuery("#result").live("click",function(e){ 
			var $clicked = $(e.target);
			var $name = $clicked.find('.name').html();
			var decoded = $("<div/>").html($name).text();
			$('#searchid').val(decoded);
		});
		jQuery(document).live("click", function(e) { 
			var $clicked = $(e.target);
			if (! $clicked.hasClass("search")){
			jQuery("#result").fadeOut(); 
			}
		});
		$('#searchid').click(function(){
			jQuery("#result").fadeIn();
		});
		});

	</script>
	<?php 
	}
	?>
</body>
</html>
