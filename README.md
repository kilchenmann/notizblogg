# Notizblogg
"Zitate | Gedanken | Ideen miteinander verknüpfen", das ist die Idee des <a href="https://notizblogg.ch">Notizbloggs</a>. Dieser ist nichts anderes als die digitale Verwirklichung eines Zettelkastens, wie er beispielsweise von Niklas Luhmann genutzt wurde.

Die Hauptaufgabe des Notizbloggs ist daher die Sammlung von Notizen und Zitaten bearbeiteter Literatur. Dabei werden die Zitate mit der entsprechenden Quelle verknüpft und für die Weiterarbeit mit LaTeX vorbereitet.

Nebst textuellem Inhalt können aber auch Bilder, Video- oder Ton-Daten aufgenommen werden. 

## Funktionen des Notizbloggs

Zentral stehen Notiz und Quelle. Erstere kann mit Bild-, Bewegtbild- oder Tonmaterial ergänzt und/oder mit einem Quellenhinweis verknüpft werden. Eine weitere Verknüpfung erfolgt über Stichworte (tags).

Eine weitere Möglichkeit der Verknüpfung besteht direkt im Textfeld. Durch das Stichwort wiki können einzelne Wörter in Verbindung zur freien Enzyklopädie Wikipedia gesetzt werden. Durch ein weiteres Stichwort kann aber auch der Notizblogg selbst nach dem markierten Wort durchsucht werden. Bsp.: <nb:Luhmann> erstellte einen Link zur Suche nach Luhmann innerhalb des Notizbloggs.

Zuletzt werden die Quellen nach den Regeln von bibLaTex erstellt. Dadurch wird es möglich, eine bib-Datei aus dem Notizblogg zu exportieren und in Latex zu importieren.

## Installation

### Datenbank
Neue MySQL-Datenbank mit dem Namen 'notizblogg' anlegen und Tabellenstruktur importieren:
    
```
mysql -u root -p -h localhost notizblogg < db-structure.sql
```

Dabei wird bereits ein Standard-Benutzer kreiert. Die Benutzerkennung ist 'admin' und 'password'. Hier die entsprechenden Daten gleich anpassen. Passwort mittels md5 verschlüsseln.

### Konfiguration
Zwei Konfigurations-Dateien müssen nun noch angepasst werden:
    
```
cp ./example-config.json ./config.json
cp ./api/controller/.conf/default-db.php ./api/controller/.conf/db.php
```
    
In der ersten Datei die URL und den Pfad des Notizbloggs anpassen. Die zweite Datei beinhaltet die Angaben zur Datenbankanbindung.
