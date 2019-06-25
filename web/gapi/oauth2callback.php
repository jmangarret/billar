<?php
include("gapi.inc.php");
//Init Google API
$client = new Google_Client();
$client->setAuthConfigFile('client_secret.json');
$client->setRedirectUri('/oauth2callback');
$client->addScope('https://www.googleapis.com/auth/youtubepartner');
$client->addScope('https://www.googleapis.com/auth/youtube');

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  $redirect_uri = '/addvideo';
  //die($redirect_uri);
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}