<?php
include("gapi/gapi.inc.php");
$htmlBody = "";
// This code will execute if the user entered a search query in the form
// and submitted the form. Otherwise, the page displays the form above.
if ($_GET)
if ($_GET['q'] && $_GET['maxResults']) {
  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);
  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);
  try {
    // Call the search.list method to retrieve results matching the specified
    // query term.
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $_GET['tipo']. ' ' .$_GET['q'],
      'maxResults' => $_GET['maxResults'],
    ));
    $videos = '';
    $channels = '';
    $playlists = '';
    // Add each result to the appropriate list, and then display the lists of
    // matching videos, channels, and playlists.
    foreach ($searchResponse['items'] as $searchResult) {
      switch ($searchResult['id']['kind']) {
        case 'youtube#video':
          $title    = $searchResult['snippet']['title'];
          $videoId  = $searchResult['id']['videoId'];
          $videos   .= '<div class="item">
                <h2 class="btn-primary">'.$title.'
                <button class="btn btn-success" style="float:right" onclick="addvideo(\''.$videoId.'\')">
                    Agregar a la cola
                </button>
                </h2>
                <div align="center">
                  <iframe class="video w100" width="640" height="360" 
                  src="https://www.youtube.com/embed/'.$videoId.'?rel=0&showinfo=0&controls=1" frameborder="0" allowfullscreen></iframe>
                </div>
                </div>';
          break;
      }
    }

    $htmlBody .= <<<END
    $videos
END;
  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  }
}

echo $htmlBody;

?>