<?php
include("gapi.inc.php");
//Init Google API
$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->setAccessType("offline");        // offline access
$client->setIncludeGrantedScopes(true);   // incremental auth
$client->addScope(Google_Service_YouTube::YOUTUBE);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/gapi/php/oauth2callback.php');

// Define an object that will be used to make all API requests.
$youtube = new Google_Service_YouTube($client);

if (isset($_GET['code'])) {
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('The session state did not match.');
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
    // This code creates a new, private playlist in the authorized user's
    // channel and adds a video to the playlist.

    // 1. Create the snippet for the playlist. Set its title and description.
    $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
    $playlistSnippet->setTitle('Test Playlist  ' . date("Y-m-d H:i:s"));
    $playlistSnippet->setDescription('A private playlist created with the YouTube API v3');

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
    
    $_SESSION['playlistId'] = $playlistId = $playlistResponse['id'];

    // 5. Add a video to the playlist. First, define the resource being added
    // to the playlist by setting its video ID and kind.
    $resourceId = new Google_Service_YouTube_ResourceId();
    $resourceId->setVideoId('SZj6rAYkYOg');
    $resourceId->setKind('youtube#video');

    // Then define a snippet for the playlist item. Set the playlist item's
    // title if you want to display a different value than the title of the
    // video being added. Add the resource ID and the playlist ID retrieved
    // in step 4 to the snippet as well.
    $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
    $playlistItemSnippet->setTitle('First video in the test playlist');
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

    $htmlBody .= "<h3>New Playlist</h3><ul>";
    $htmlBody .= sprintf('<li>%s (%s)</li>',
        $playlistResponse['snippet']['title'],
        $playlistResponse['id']);
    $htmlBody .= '</ul>';

    $htmlBody .= "<h3>New PlaylistItem</h3><ul>";
    $htmlBody .= sprintf('<li>%s (%s)</li>',
        $playlistItemResponse['snippet']['title'],
        $playlistItemResponse['id']);
    $htmlBody .= '</ul>';

  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  }

  $_SESSION['token'] = $client->getAccessToken();
} else {
  // If the user hasn't authorized the app, initiate the OAuth flow
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;

  $authUrl = $client->createAuthUrl();

 // header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));

  $htmlBody = <<<END
  <h3>Authorization Required</h3>
  <p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
}
?>

<!doctype html>
<html>
<head>
<title>New Playlist</title>
</head>
<body>
  <?=$htmlBody?>
  <? //include("list.php"); ?>
</body>
</html>