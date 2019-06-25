<?php
include("gapi/gapi.inc.php");
//Init Google API
$client = new Google_Client();
$client->setAuthConfigFile('gapi/client_secret.json');
$client->setRedirectUri($redirect);
$client->addScope('https://www.googleapis.com/auth/youtubepartner');
$client->addScope('https://www.googleapis.com/auth/youtube');
//var_dump($client)
if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  $redirect_uri = '/karaokesearch';
  //die(filter_var($redirect_uri, FILTER_SANITIZE_URL));
 //  $redirect_uri = filter_var($redirect_uri, FILTER_SANITIZE_URL);
}

?>
<script>
	document.location.href='<?=$redirect_uri?>';
</script>