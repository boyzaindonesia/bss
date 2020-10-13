<?php
session_start();
include_once("fbapi/autoload.php");

$redirect = 'http://www.butiksasha.com/oAuth/facebook/';

# Set the default parameters
$fb = new Facebook\Facebook([
  'app_id' 					=> '210774252730991', //butik sasha
  'app_secret' 				=> '9c362549686aa98aef454bdc2bcf9482',
  'default_graph_version' 	=> 'v2.8',
]);

# Create the login helper object
$helper = $fb->getRedirectLoginHelper();

# Get the access token and catch the exceptions if any
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

# If the 
if (isset($accessToken)) {
  	// Logged in!
 	// Now you can redirect to another page and use the
		// access token from $_SESSION['facebook_access_token'] 
		// But we shall we the same page

	// Sets the default fallback access token so 
	// we don't have to pass it to each request
	$fb->setDefaultAccessToken($accessToken);

	try {
	  $response = $fb->get('/me?fields=email,name');
	  $userNode = $response->getGraphUser();
	}catch(Facebook\Exceptions\FacebookResponseException $e) {
	  // When Graph returns an error
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  // When validation fails or other local issues
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}

	$image = 'https://graph.facebook.com/'.$userNode->getId().'/picture?width=200';
	?>

	<!doctype html>
	<html>
	<body onload="document.form1.submit()">
	   <form name="form1" action="http://localhost/butiksasha/authorize" method="post">
	    	<input type="hidden" name="id" value="<?php echo $userNode->getId(); ?>" />
	    	<input type="hidden" name="name" value="<?php echo $userNode->getName(); ?>" />
	    	<input type="hidden" name="email" value="<?php echo $userNode->getProperty('email'); ?>" />
	    	<input type="hidden" name="image" value="<?php echo $image ?>" />
	    	<input type="hidden" name="gender" value="" />
	    	<input type="hidden" name="locale" value="" />
	    	<input type="hidden" name="link" value="" />
	    	<input type="hidden" name="method" value="facebook" />
	   </form>
	</body>
	</html>
<?php
} else {
	$permissions  = ['email'];
	$loginUrl = $helper->getLoginUrl($redirect,$permissions);
	// echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
	header('location: '.$loginUrl);
}
?>