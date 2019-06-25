<?php
include_once("gapi/gapi.inc.php");
//Init Google API
$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$client->setScopes('https://www.googleapis.com/auth/youtube');
$client->setRedirectUri($redirect);
// Define an object that will be used to make all API requests.
$youtube = new Google_Service_YouTube($client);
$htmlList = '';    
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
      //Buscamos items de la lista
      $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet', array(
        'playlistId' => $_SESSION['playlistid'],
        'maxResults' => 50
      ));
      $htmlList .= '<ul>';
      $htmlList .= '<h3>Lista de Reproduccion '.date('d-m-Y').'</h3>';
      foreach ($playlistItemsResponse['items'] as $playlistItem) {
        $htmlList .= sprintf('<li>%s</li>', $playlistItem['snippet']['title']);
      }
      $htmlList .= '<ul>';
  } catch (Google_Service_Exception $e) {
   $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;

  $authUrl = $client->createAuthUrl();

$htmlList = <<<END
  <h3>Inicio de Sesion Requirido!</h3>
  <p>Ingrese a <a href="$authUrl">Autorizar acceso</a> para continuar...<p>
END;
  } catch (Google_Exception $e) {
    $htmlList .= sprintf('<p>Error 2: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  }

  $_SESSION['token'] = $client->getAccessToken();
} else {
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;

  $authUrl = $client->createAuthUrl();

$htmlList = <<<END
  <h3>Inicio de Sesion Requirido!</h3>
  <p>Ingrese a <a href="$authUrl">Autorizar acceso</a> para continuar...<p>
END;
}

echo $htmlList;

?>
