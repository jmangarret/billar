<?php
include_once("gapi/gapi.inc.php");
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
    // This code creates a new, private playlist in the authorized user's
    // channel and adds a video to the playlist.

    // 1. Create the snippet for the playlist. Set its title and description.
    $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
    $playlistSnippet->setTitle('Lista de Reproduccion ' . date("d-m-Y H:i:s"));
    $playlistSnippet->setDescription('Videos y Karaokes Billar');

    // 2. Define the playlist's status.
    $playlistStatus = new Google_Service_YouTube_PlaylistStatus();
    $playlistStatus->setPrivacyStatus('private');

    // 3. Define a playlist resource and associate the snippet and status
    // defined above with that resource.
    $youTubePlaylist = new Google_Service_YouTube_Playlist();
    $youTubePlaylist->setSnippet($playlistSnippet);
    $youTubePlaylist->setStatus($playlistStatus);

    // 4. Call the playlists.insert method to create the playlist. The API
    // response will contain information about the new playlist.
    $playlistResponse = $youtube->playlists->insert('snippet,status',
        $youTubePlaylist, array());
    
    $_SESSION['playlistid'] = $playlistId = $playlistResponse['id'];


  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>Error: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>Error: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  }

  $_SESSION['token'] = $client->getAccessToken();
} 

echo $htmlBody;
?>
