<?php

$schoolname = "_xxxxxxxxxxxxxxxxxxxxxx=="; // Schulname - Zu finden in den Cookies *EDIT*
$datum = date('Y-m-d', strtotime(' + 1 days')); // Aufbau des Datums für den iCal-Kalender
$url = "https://mese.webuntis.com/WebUntis/Ical.do?elemType=1&elemId=XXX&rpt_sd=". $datum; // Die Download-URL zum Kalender; elemId einfügen *EDIT*

setlocale(LC_TIME, array('de_DE.UTF-8','de_DE@euro','de_DE','german')); // Setzen der Sprache und Zeitzone (optional)

// Cookie setzen
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Cookie: schoolname=".$schoolname.""
  )
);
$context = stream_context_create($opts);
// Datei mit dem oben gesetzen Cookie öffnen (erforderlich, wird ohne Cookie nicht erlaubt)
$file = file_get_contents($url, false, $context);
if ($file === false) {
  echo "Fehler beim laden der iCal-Datei.\n";
  die();
}
// Datei als aktuell.ics speichern
file_put_contents('aktuell.ics', $file); // chmod 777 nicht vergessen!




try {
  echo "Laden der iCal-Datei erfolgreich..\n\n";
require 'class.iCalReader.php';
ob_start(); // Buffering starten

$ical   = new ICal("aktuell.ics"); // chmod 777 nicht vergessen!
$events = $ical->events();

  try {
    function sortByName($a, $b)
    {
        $a = $a['DTSTART'];
        $b = $b['DTSTART'];

        if ($a == $b) return 0;
        return ($a < $b) ? -1 : 1;
    }

    usort($events, 'sortByName');
  } catch (\Exception $e) {
    echo "Fehler bei der Sortierung!\n". $e->getMessage();
    die();
  }



//Output Schleife pro $event
foreach ($events as $event) {

// DTSTART und DTEND zu einem normalen Datum machen
$dtstart = strtotime($event['DTSTART']);
$dtend = strtotime($event['DTEND']);
    echo "Fach: *".$event['SUMMARY']."*\n";
    echo "Start: ". strftime("%A, %d. %B %Y %H:%M Uhr", $dtstart)."\n";
    echo "Ende: ". strftime("%A, %d. %B %Y %H:%M Uhr", $dtend)."\n";
    echo "Lehrer: ".$event['DESCRIPTION']."\n";
    echo "Raum: *".$event['LOCATION']."*\n";
    echo "\n\n";
}

$aktuell = file_get_contents("aktuell.html");// chmod 777 nicht vergessen!
// Stundenplan vergleichen
if ($aktuell == ob_get_contents()) { // Wenn die aktuelle Datei == die alte Datei ist..
  echo "Keine Neuerung seit: ".date("l, d.m.Y H:i:s",filemtime("aktuell.html"))." Uhr.";// chmod 777 nicht vergessen!
}else {
// Bei Neuerung..
$ob_get_contents = ob_get_contents();
file_put_contents('aktuell.html', $ob_get_contents); // Bei Neuerung aktuell.html mit neuen Stundenplan Daten erstellen; chmod 777 nicht vergessen!
echo "Neuerung ";

/* Telegram Code */
function sendMessage($chatID, $messaggio, $token) {
    echo "Nachricht gesendet an " . $chatID . "\n"; // Info die auf der Website ausgegeben wird.(optional)
    $url = "https://api.telegram.org/" . $token . "/sendMessage?parse_mode=markup&chat_id=" . $chatID;
    $url = $url . "&text=" . urlencode($messaggio);
    $ch = curl_init();
    $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

}
$token = "bot123456789:XXXXXXXXXXX-XXXXXXXXXXXXXXXXXXXXXXX"; // Telegram BOT Token
$ch1 = curl_init("https://api.telegram.org/$token/setWebhook");
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_exec($ch1);

$chatID = "123456789"; // ChatID von Telegram

sendMessage($chatID,"Vorher:\n$aktuell",$token); // Funktion der Nachricht; Vorherigen Stundenplan senden
sendMessage($chatID,"Nachher:\n$ob_get_contents",$token); // Funktion der Nachricht; Aktuellen Stundenplan senden
sendMessage($chatID,'Irgendwas hat sich geändert bei Untis.',$token); // Nachricht für Änderung senden
}
} catch (\Exception $e) {
  echo "Fehler bei der Verarbeitung. ".$e->getMessage(). "\n";
  die();
}
 ?>
