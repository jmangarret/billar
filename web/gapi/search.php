<?php
include("gapi.inc.php");
//Init Google API
$htmlBody = '
<form method="GET">
  <div>
    Search Term: <input type="search" id="q" name="q" placeholder="Enter Search Term">
  </div>
  <div>
    Max Results: <input type="number" id="maxResults" name="maxResults" min="1" max="50" step="1" value="25">
  </div>
  <input type="submit" value="Search">
</form>
';
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
      'q' => $_GET['q'],
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
          $videos .= sprintf('<li>%s (%s) <button id="%s" class="addButton">Add</button></li>',
              $searchResult['snippet']['title'], $searchResult['id']['videoId'], $searchResult['id']['videoId']);
          break;
        case 'youtube#channel':
          $channels .= sprintf('<li>%s (%s)</li>',
              $searchResult['snippet']['title'], $searchResult['id']['channelId']);
          break;
        case 'youtube#playlist':
          $playlists .= sprintf('<li>%s (%s)</li>',
              $searchResult['snippet']['title'], $searchResult['id']['playlistId']);
          break;
      }
    }
    $htmlBody .= <<<END
    <h3>Videos</h3>
    <ul>$videos</ul>
    <h3>Channels</h3>
    <ul>$channels</ul>
    <h3>Playlists</h3>
    <ul>$playlists</ul>
END;
  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  }
}

?>
<!doctype html>
<html>
  <head>
    <title>YouTube Search</title>
  </head>
  <body>
    <?=$htmlBody?>
        <input type="text" id="playlistId" value=" <?=$_SESSION['playlistId']?>">

  </body>
</html>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>  
  $(".addButton").on("click", function(event){
    event.preventDefault(); 
    var video    = $(this).attr('id');
    var playlist = $("#playlistId").val();
    alert (video+ ' - '+ playlist );
    $.ajax({
      url : "addvideo.php",
      data : {
        "id" : video,
        "playlistid" : playlist
      },
      success : function(response){
        console.log(response)
      },
      error : function(xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(thrownError);
      }
    });
});
</script>
