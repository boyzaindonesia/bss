<?php
session_start();
include_once("inc/twitteroauth.php");

define('CONSUMER_KEY', '7qSCRofWhZmshTfoK0Ct6na9z');
define('CONSUMER_SECRET', '9XTIIkzGM26qMIdyUGQQDkUiWaoTrTNRpqOKwNGmwau8Q6SZAY');
define('OAUTH_CALLBACK', 'http://butiksasha.com/oAuth/twitter/');

if(isset($_REQUEST['oauth_token']) && $_SESSION['token'] !== $_REQUEST['oauth_token']) {

	//If token is old, distroy session and redirect user to index.php
	// session_destroy();
	// header('Location: dashboard.php');

} else if(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']) {

	//Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['token'] , $_SESSION['token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	if($connection->http_code == '200'){

		$user_info = $connection->get('account/verify_credentials'); 
		$name = explode(" ",$user_info->name);
		$fname = isset($name[0])?$name[0]:'';
		$lname = isset($name[1])?$name[1]:'';
		// echo $user_info->id.'  <br/>';
		// echo $user_info->name.'  <br/>';
		// echo $user_info->screen_name.'  <br/>';
		// echo $user_info->lang.'  <br/>';
		// echo $user_info->profile_image_url.'  <br/>';
		?>
    <!doctype html>
    <html>
    <body onload="document.form1.submit()">
        <form name="form1" action="http://butiksasha/authorize" method="post">
            <input type="hidden" name="id" value="<?php echo $user_info->id; ?>" />
            <input type="hidden" name="name" value="<?php echo ucwords($user_info->name); ?>" />
            <input type="hidden" name="email" value="" />
            <input type="hidden" name="gender" value="" />
            <input type="hidden" name="locale" value="" />
            <input type="hidden" name="link" value="" />
            <input type="hidden" name="image" value="<?php echo $user_info->profile_image_url; ?>" />
            <input type="hidden" name="method" value="twitter" />
        </form>
    </body>
    </html>
    <?php
	} else {
		die("error, try again later!");
	}
} else {
	if(isset($_GET["denied"])){
		die("error connecting to twitter! try again later!");
	}

	//Fresh authentication
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
	
	//Received token info from twitter
	$_SESSION['token'] 			= $request_token['oauth_token'];
	$_SESSION['token_secret'] 	= $request_token['oauth_token_secret'];
	
	//Any value other than 200 is failure, so continue only if http code is 200
	if($connection->http_code == '200'){
		//redirect user to twitter
		$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
		header('Location: ' . $twitter_url); 
		exit();
	} else {
		die("error connecting to twitter! try again later!");
	}
}
?>