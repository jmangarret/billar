<?php
include("gapi/gapi.inc.php");
//No vaciar aca
//$_SESSION["playlistid"]="";

$htmlBody="";

if (!$_SESSION['playlistid']){
  include_once('insert.html.php');
}

$videoId = $_REQUEST["id"];
$playlistId = $_SESSION["playlistid"];
//Init Google API
$client = new Google_Client();
$client->setAuthConfig('gapi/client_secret.json');
$client->setAccessType("offline");        // offline access
$client->setIncludeGrantedScopes(true);   // incremental auth
$client->addScope(Google_Service_YouTube::YOUTUBE);
$client->setRedirectUri($redirect);
// Define an object that will be used to make all API requests.
$youtube = new Google_Service_YouTube($client);
if (isset($_GET['code'])) {
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('El estado de la sesión no coincide.');
  }
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: ' . $redirect);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}
// Check to ensure that the access token was successfully acquired.
if ($client->getAccessToken()) {
  try {
      // 5. Add a video to the playlist. First, define the resource being added
    // to the playlist by setting its video ID and kind.
    $resourceId = new Google_Service_YouTube_ResourceId();
    $resourceId->setVideoId($videoId);
    $resourceId->setKind('youtube#video');

    // Then define a snippet for the playlist item. Set the playlist item's
    // title if you want to display a different value than the title of the
    // video being added. Add the resource ID and the playlist ID retrieved
    // in step 4 to the snippet as well.
    $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
    $playlistItemSnippet->setTitle('Video '.date('dmYHis'));
    $playlistItemSnippet->setPlaylistId($playlistId);
    $playlistItemSnippet->setResourceId($resourceId);

    // Finally, create a playlistItem resource and add the snippet to the
    // resource, then call the playlistItems.insert method to add the playlist
    // item.
    $playlistItem = new Google_Service_YouTube_PlaylistItem();
    $playlistItem->setSnippet($playlistItemSnippet);
    $playlistItemResponse = $youtube->playlistItems->insert(
        'snippet,contentDetails', $playlistItem, array()
    );

} catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>Error: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>Error: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  }

  $_SESSION['token'] = $client->getAccessToken();
} else {
  // If the user hasn't authorized the app, initiate the OAuth flow
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;

  $authUrl = $client->createAuthUrl();

$htmlBody = <<<END
  <h3>Inicio de Sesion Requirido!</h3>
  <p>Ingrese a <a href="$authUrl">Autorizar acceso</a> para continuar...<p>
END;
}

if ($_SESSION['playlistid']){
  include_once('list.html.php');
}

echo $htmlBody;

?>



