redaxo_yrewrite
================

Ein Multidomain-Rewriter Addon für REDAXO CMS >= 5.0

Mit diesem AddOn lassen sich verschiedenen Domains innerhalb von REDAXO
bestimmten Kategorien, auch sprachabhängig, zuweisen.

Somit kann man mehrere Webseiten in einer Installation verwalten.
Jede Domain kann eine eigene Startsprache haben oder für bestimmte
Sprachen nur zuständig sein.
Es wäre z.B. auch möglich bei englisch nur eine .com Domain zu verwenden, und bei
deutsch die entsprechende .de Domain. Oder auch nur eine Domain mit
Hauptsprache englisch. Die deutschen URL würden dann auf der englischen
Domain mit Sprachkürzel dargestellt werden.


Funktionsliste
-------

* Mehrere Domains in einer Webseite verwaltbar
* Sprachabhängigkeiten von Domains zuweisbar
* SEO Features: Domain- und sprachabhängige robots und sitemap Dateien
* Individuelle URL pro Artikel möglich
* Seitentitel Schema definierbar / pro Domain/Sprache
* Alias Domains die auf die Hauptdomain verweisen
* Allgemeine Weiterleitungen. URLs zu internen Artikeln, Dateien, externen Artikeln
* Canonical Urls

Installation
-------

* Release herunterladen und entpacken.
    * Die aktuelle github Version nur als Entwicklerversion betrachten
* Umbenennen in yrewrite
* In den REDAXO 5 AddOnordner legen /redaxo/src/addons/

oder

* Über das REDAXO Backend installieren und aktivieren
* Im YRewriter die Domain/s eintragen und zuweisen


Last Changes
-------

### Version 2.2 // 19.09.2016

#### Bugs
- Weiterleitungen funktionierten unter bestimmten Situationen nicht
- Änderungen an Domainnamen werden nun konsequent referenziert.


### Version 2.1 // 25.08.2016

#### Neu
- Diverse Beschreibung/Erklärungen ergänzt
- Methode getCurrentDomain ergänzt
- Diverse Sonderzeichen bei Ersetzungen ergänzt
- Schwedische Sprache ergänzt
- HreflangTag geändert
- extension point "YREWRITE_HREFLANG_TAGS" hinzugefügt
- robots.txt darf nun auch leer sein
- Custom-Urls nun auch mit "/" am Ende erlaubt
- Optional kann nun bei der Startsprache der language slug ausgeschaltet werden
- rex::getServer() wird nicht mehr überschrieben
- www. und http/s werden je nach konfigurierter Domain automatisch umgeleitet
- getFullUrlByArticleId um params und separator wie bei rex_getUrl ergänzt

#### Bugs
- Startseitenerkennung korrigiert.
- Automatische Weiterleitung bei fehlendem Suffix
- Sitemap wird nun auch bei der default Domain ausgegeben
- Default clang immer auf aktuelle gesetzt
- ETag deaktiviert
- htaccess. files Ordner auf media geändert
- "Doppelter Slash"-Problem korrigiert
- Sitemap enthält keine 404 Seiten mehr
- Pfadaufruf für Windows angepasst
- Bestimmte Weiterleitung hatten nicht funktioniert.
- Weiterleitung auf Dateien ist korrigiert
- Umgang mit der default-Domain verbessert

#### Info
- Textänderung: Startpunkt -> Mountpoint
- Diverse fehlende Texte ergänzt



### Version 2.0.1 // 11.02.2016

- Texte ergänzt / deutsch und englisch
- rex_yrewrite_scheme ergänzt um getSuffix() und aufruf von normalize() erlaubt und getScheme() ergänzt
- Fehlerinfo wenn noch keine Domain vorhanden ist.

#### Bugs

- Caching in der htaccess angepasst. Ist zu unkontrolliert und allgemein. Deswegen erstmal draußen
- Workaround für RewriteBase, allgemeingültig gemacht



### Version 2.0 // 02.02.2016

- Portierung zu Redaxo 5
- Unterordner sind nun möglich
- http oder https, wird direkt in der Domainbezeichnung festgelegt
- Meta langhref ergänzt
- Fürs Verständnis aus "undefined" -> "default" gemacht.
- Standard-URL-Schema geändert .. aus .html -> /
- Canonical Urls ergänzt

