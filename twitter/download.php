<?php


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
$content = $connection->get('statuses/home_timeline');


if(isset($_POST['submit1']))
{


 if($_POST['file_format']=="xls")
{
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=All_Tweets.xls");
	$output = fopen('php://output', 'w');
	
	
	fwrite($output,"Name \t Tweet \n");
	//$i=0;
	foreach($content as $item){
	
	$row= $item->user->name." \t ".$item->text."\n";
	
	fwrite($output,$row);
	
	} 
	readfile($output);

}else if($_POST['file_format']=="xml")
{
	$xml = new SimpleXMLElement('<xml/>');
	
	foreach($content as $item){
		$track = $xml->addChild('User Tweet');
		$track->addChild('Name', $item->user->name);
		$track->addChild('Tweet', $item->text);
	}
	$name = "All_Tweets.xml";//strftime('backup_%m_%d_%Y.xml');
		header('Content-Disposition: attachment;filename=' . $name);
		header('Content-Type: text/xml');
		print($xml->asXML());


}else if($_POST['file_format']=="json")
{
	
	$response = array();
	$posts = array();
	
	
	foreach($content as $item)
	{ 
	$name=$item->user->name; 
	$tweet=$item->text; 
	
	$posts[] = array('Name'=> $name, 'Tweet'=> $tweet);
	
	} 
	
	$response['posts'] = $posts;
	
	header('Content-disposition: attachment; filename=All_Tweets.json');
	header('Content-type: application/json');
	echo json_encode($response);

}else if($_POST['file_format']=="pdf")
{

	require('lib/fpdf.php');
	
	class PDF extends FPDF
	{
	  function Header()
		{
		  $this->SetFont('Helvetica','B',15);
		  $this->SetXY(50, 10);
		  $this->Cell(0,10,'Tweet',1,0,'C');
		 }
	}
		// send the generated pdf to the browser
			header("Content-Type: application/pdf");
			header("Cache-Control: no-cache");
			header("Accept-Ranges: none");
			header("Content-Disposition: attachment; filename=\"All_Tweets.pdf\"");
	$pdf=new PDF();
	$pdf->AddPage();
	
	$pdf->SetFont('Arial','B',16);
	foreach($content as $item)
	{ 
	$text = "@".$item->user->name." : ".$item->text;
	$pdf->Cell(40,10,$text);
	}
	
	$pdf->Output('All_Tweets.pdf','D');

}else
{
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=All_Tweets.csv');
	
	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');
	// output the column headings
	fputcsv($output, array('Name', 'Tweet'));
	
	
	foreach($content as $item)
	{ 
	$row[0]= $item->user->name;
	$row[1]= $item->text;
	
	fputcsv($output,$row);
	
	} 
	readfile($output);
}

	 
}

?>