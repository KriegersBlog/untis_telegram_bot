# Untis-Telegram-Bot
![Untis Stundenplan Push](https://firatcan.de/untis/logo.png)

**Benachrichtigungsbot für Stundenplanänderungen**


Dieser Bot fragt regelmäßig über einen *Cronjob* den aktuellen Stundenplan einer Untis-Klasse ab und vergleicht ihn auf Veränderungen.

Dafür wird der Stundenplan bei einer Änderung auf *aktuell.html* und *aktuell.ics* gespeichert.

Wenn der aktuelle Stundenplan eine Änderung aufweist wird über einen Telegram-Bot eine Nachricht (z.B. Pushnachricht) an die angegebene Chat-ID gesendet.


> Der Bot ist zurzeit alles andere als effizient, da er jede Minute den Kalender abfragt/runterlädt!

## Was wird benötigt?
* PHP *(ich nutze PHP7)*
* [iCalReader *(class.iCalReader.php)*](https://github.com/MartinThoma/ics-parser)
* [Telegram Bot](https://core.telegram.org/bots/api)
* [Cronjobs](https://de.wikipedia.org/wiki/Cron)

## Einrichtung / Installation
1. Die Dateien der Repository in ein Web-Verzeichnis kopieren.
2. Token, ChatID, Schulname-Cookie usw in der ```index.php``` eintragen. 
3. ```chmod 777 aktuell.html && aktuell.ics ``` ausführen.
4. Cronjobs einrichten.
5. Fertig!

### Cronjobs (beispiel)
Jede Minute von 6-15 Uhr von Mo-Fr.

Einmal jede Stunde jeden Tag.
```
#m	h	dom	mon	dow	command

*	6-15	*	*	1-5	wget -O /dev/null -q https://example.com/path/to/index.php
0	*	*	*	*	wget -O /dev/null -q https://example.com/path/to/index.php
```
