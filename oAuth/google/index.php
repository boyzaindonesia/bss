<?php
session_start();
include_once("src/Google_Client.php");
include_once("src/contrib/Google_Oauth2Service.php");

// Id Butik sasha
$clientId     = '540187067088-6qdhp0obfbktj51jai0a1vucloe6sl0e.apps.googleusercontent.com'; //Google CLIENT ID
$clientSecret = 'XQkKAM3uD5T_GGnTcsVNJUJF'; //Google CLIENT SECRET
$redirectUrl  = 'http://localhost/butiksasha/oAuth/google/';  //return url (url to script)

##################################

$gClient = new Google_Client();
$gClient->setApplicationName('butiksasha');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectUrl);

$google_oauthV2 = new Google_Oauth2Service($gClient);

//print_r($_GET);die;

if(isset($_REQUEST['code'])){
	$gClient->authenticate();
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($redirectUrl, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
	$userProfile = $google_oauthV2->userinfo->get();
?>
    <!doctype html>
    <html>
    <body onload="document.form1.submit()">
        <form name="form1" action="http://localhost/butiksasha/authorize" method="post">
            <input type="hidden" name="id" value="<?php echo $userProfile['id']; ?>" />
            <input type="hidden" name="name" value="<?php echo $userProfile['given_name'].' '.$userProfile['family_name']; ?>" />
            <input type="hidden" name="email" value="<?php echo $userProfile['email']; ?>" />
            <input type="hidden" name="gender" value="<?php echo $userProfile['gender']=='male'?'1':'0'; ?>" />
            <input type="hidden" name="locale" value="<?php echo $userProfile['locale'] ?>" />
            <input type="hidden" name="link" value="<?php echo $userProfile['link'] ?>" />
            <input type="hidden" name="image" value="<?php echo $userProfile['picture'] ?>" />
            <input type="hidden" name="method" value="google" />
        </form>
    </body>
    </html>
<?php 
} else {
	$authUrl = $gClient->createAuthUrl();
}

if(isset($authUrl)) { 
	// echo '<a href="'.$authUrl.'"><img src="images/glogin.png" alt=""/></a>';
	header('location: '.$authUrl);
	exit();
// } else {
	// echo '<a href="logout.php?logout">Logout</a>';
}
?>