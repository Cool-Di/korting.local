events-&gt;listEvents($apiConfig[&quot;calendar_ID&quot;]); $COLORS = $cal-&gt;colors-&gt;get(); //echo &quot; 
<pre>&quot;.print_r($EVENTS, 1).&quot;</pre>
 &quot;; /*OAUTH // Visit https://code.google.com/apis/console?api=calendar to generate your // client id, client secret, and to register your redirect uri. $client-&gt;setClientId('942289484950-b0erpqrf2t8of296e9f9g39mri92qfgf.apps.googleusercontent.com'); $client-&gt;setClientSecret('wVK8_Q4ZT8OnoMbOnQU2Vufa'); $client-&gt;setRedirectUri('http://academy.korting.ru/calendar/'); $client-&gt;setDeveloperKey('AIzaSyAGTb4h64k3Tiuj4ZEVtFAzEA8z4JebBkA'); //print_r($client-&gt;setDeveloperKey('AIzaSyAWaxCTYsXhCTLYFPKcRWpbSnNB5KxGL7s')); $cal = new apiCalendarService($client); if (isset($_GET['logout'])) { unset($_SESSION['token']); } if (isset($_GET['code'])) { $client-&gt;authenticate(); $_SESSION['token'] = $client-&gt;getAccessToken(); header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']); } if (isset($_SESSION['token'])) { $client-&gt;setAccessToken($_SESSION['token']); } if ($client-&gt;getAccessToken()) { //$calList = $cal-&gt;calendarList-&gt;listCalendarList(); //print &quot; 
<h1>Calendar List</h1>
 
<pre>&quot; . print_r($calList, true) . &quot;</pre>
 &quot;; 	$EVENTS = $cal-&gt;events-&gt;listEvents($apiConfig[&quot;calendar_ID&quot;]); 	$COLORS = $cal-&gt;colors-&gt;get(); $_SESSION['token'] = $client-&gt;getAccessToken(); } else { $authUrl = $client-&gt;createAuthUrl(); print &quot;<a class="login" href="$authUrl" >Connect Me!</a>&quot;; } */