<?php
require_once 'src/apiClient.php';
require_once 'src/contrib/apiCalendarService.php';

session_start();

$client = new apiClient();

/*WITHOUT OAUTH*/
$cal = new apiCalendarService($client);
$EVENTS = $cal->events->listEvents($apiConfig["calendar_ID"]);
$COLORS = $cal->colors->get();
//echo "<pre>".print_r($EVENTS, 1)."</pre>";
/*OAUTH
// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.


 $client->setClientId('942289484950-b0erpqrf2t8of296e9f9g39mri92qfgf.apps.googleusercontent.com');
 $client->setClientSecret('wVK8_Q4ZT8OnoMbOnQU2Vufa');
 $client->setRedirectUri('http://academy.korting.ru/calendar/');
 $client->setDeveloperKey('AIzaSyAGTb4h64k3Tiuj4ZEVtFAzEA8z4JebBkA');

//print_r($client->setDeveloperKey('AIzaSyAWaxCTYsXhCTLYFPKcRWpbSnNB5KxGL7s'));

$cal = new apiCalendarService($client);
if (isset($_GET['logout'])) {
  unset($_SESSION['token']);
}

if (isset($_GET['code'])) { 
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
  //$calList = $cal->calendarList->listCalendarList();
  //print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
	$EVENTS = $cal->events->listEvents($apiConfig["calendar_ID"]);
	$COLORS = $cal->colors->get();

$_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}
*/