Artikelsystem (articlesystem)
====================

Bechreibung
---------------------------------------------------------------------------
Ein Plugin zur Verwaltung von frei definierbaren Artikeln (z.B. News, 
Presse-Meldungen aber auch Produktverzeichnisse u.v.a.m.) ... inkl.
Ausgabemodul zur Listen und Detail-Darstellung der Artikel

Features
---------------------------------------------------------------------------
* Titel, Teaser, Text und eine unbegrenzte Anzahl von (optionalen)
  Bildern, Dateien, Links als Basisartikelbestandteile.
* Zusätzliche können Artikel mittels 35 freidefinierbare Feldern
  (Textzeile, Text, formatierbarer Text, Auswahl von Werten, 
  Eingabe und Auswahl von Werten, Datum, Zeit, Checkbox-Werte, 
  Radio-Auswahl, Bild, Datei, Link) speziell individualisiert werden.
* Artikelkategorien mit optionaler Mehrfachzuordnung pro Artikel.
* Unterstützt mehrsprachige Websites: Artikel können parallel in jeder 
  Sprache angelegt werden, Inhalte können von Sprache zu Sprache kopiert 
  werden.
* Suchfunktion mit Logik-features.
* Vielfältig konfigurierbare Artikel-Eingabe/-Verwaltung und -Ausgabe.
* Datum-/Zeitgesteuerte Artikelausgabe, Zeitraum-/Monats-/Jahresnavigation
* Artikellistenausgabe mit vielfältigen Sortier- und Filtermöglichkeiten
* Artikellisten- und Artikeldetailausgabe sind komplett per Templates 
  konfigurierbar.
* Pseudo-Mehrfachinstallation des Artikelsystems innerhalb einer 
  Sefrengo-Installation: es können mehre "Artikelsysteme" pro Sefrengo-
  Installation angelegt und in einer Websites gleichzeitig genutzt werden.

Autor(en)
---------------------------------------------------------------------------
* Alexander M. Korn (amk) (v0.1.0-v1.6.10)
* Holger Stitz (Terminkalender v1.2.5 - als Basis)

Benötigte Sefrengo Version
---------------------------------------------------------------------------
&gt;= 01.04.00

Installation
---------------------------------------------------------------------------
Wechseln Sie in Ihrer Sefrengo Version in den Bereich "Administration->
Plugins". Wechseln Sie dort in den Bereich "Plugin importieren"). Am
unteren Ende des Bereichs befindet sich ein Uploadfeld. Wählen Sie hier die
gewünschte "*.cmsplugin"- Datei aus. Mit einem Klick auf das Diskettensymbol
wird das Plugin in das CMS importiert. Das Plugin ist nun innerhalb des CMS
nutzbar.

Mit der Installation des Plugin wird automatisch im Modul-Import-Bereich das
Ausgabemodul installiert. Wechseln Sie in Ihrer Sefrengo Version in den 
Bereich "Design->Module->Modul importieren" und importieren Sie das Modul, 
um es in ein Sefrengo-Template einbinden zu können.

Sollte es nach der Installation zu Fehlermeldungen kommen, ersetzen Sie bitte
die Dateien Ihrer Sefrengo-Installation, welche sich in
[der Zip-Datei](https://github.com/sefrengo-cms/articlesystem/raw/wiki/files/backend_add_files_4_lt-SF141.zip)
befinden.


Installation "mehrerer Artikelsysteme" innerhalb einer Sefrengo-Installation
---------------------------------------------------------------------------
Das Artikelsystem 1.6 ermöglicht es beliebig viele "Artikelsysteme" 
anzulegen, welche völlig autonom konfiguriert und genutzt werden können
(auch genannt Multi-Datenbank-Feature).
Zu diesem Zweck findet man nach der regulären Installation des 
Artikelsystem-Plugin im Backend unter "Administration" einen zusätzlichen
Menüpunkt "Artikelsystem" zur entsprechenden Verwaltung (Anlegen/Löschen 
einer neuen Artikelsystem-Datenbank ergo eines weiteren "Artikelsystems").
Wird ein zusätzliches "Artikelsystem" angelegt, erscheint ein entsprechender
neuer Artikelsystem-Menüpunkt im Backend unter "Redaktion". 
Selbstverständlich kann dieser Menüpunkt individuell und sprachabhängig 
in den Artikelsystem-Einstellungen benannt werden.
Sofern mehr als eine Artikelsystem-Datenbank vorhanden ist, kann in jeder
Artikelsystem-Ausgabemodul-Installation/-Konfiguration ausgewählt werden,
welches "Artikelsystem" vom Modul genutzt werden soll ergo welche Inhalte
ausgegeben werden sollen.

Dokumentation
---------------------------------------------------------------------------
<https://github.com/sefrengo-cms/articlesystem/wiki>
