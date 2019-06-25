<?php
require_once 'vendor/autoload.php';
include 'Google/Client.php';
include 'Google/Service/YouTube.php';
/*
 * You can acquire an OAuth 2.0 client ID and client secret from the
 * Google Developers Console <https://console.developers.google.com/>
 * For more information about using OAuth 2.0 to access Google APIs, please see:
 * <https://developers.google.com/youtube/v3/guides/authentication>
 * Please ensure that you have enabled the YouTube Data API for your project.
 */
$OAUTH2_CLIENT_ID = '994133950693-1dq3odn9nsglbif826fn5rfg2c1lja8t.apps.googleusercontent.com';
$OAUTH2_CLIENT_SECRET = '8Kp7386ApOgHdJDvgyPI82tf';
/*
* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
* Google Developers Console <https://console.developers.google.com/>
* Please ensure that you have enabled the YouTube Data API for your project.
*/
$DEVELOPER_KEY = 'AIzaSyAAlWToOBS1ysvNuCKFBdCVtYER3TmowU0';

$redirect = filter_var('http://127.0.0.1:8000/oauth2callback' ,FILTER_SANITIZE_URL);


?>