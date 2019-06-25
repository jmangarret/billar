<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->addScope('https://www.googleapis.com/auth/youtubepartner');
$client->addScope('https://www.googleapis.com/auth/youtube');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $youtube = new Google_Service_Youtube($client);
  $files = $youtube->channels->listChannels('contentDetails', array(
      'mine' => 'true',
    ));
  echo json_encode($files);
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/gapi/php/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}