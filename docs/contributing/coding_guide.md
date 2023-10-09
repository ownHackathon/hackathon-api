# Programmierleitfaden

In der Programmierung und das in einem Team, ist es unsagbar wichtig, einen einheitlichen Code Stil zu haben.
Wir benutzen und halten uns hier an die akzeptieren PSR von [PHP-FIG](https://www.php-fig.org/)

## Code-Stil

Für die Formatierung vom Code halten wir uns an den [PSR-12](https://www.php-fig.org/psr/psr-12/) mit aber einem kleinen
Aber: Entgegengesetzt von [PSR-12](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-12-extended-coding-style-guide.md) fügen wir `declare(strict_types=1);` in die erste
Zeile ein.

Beispiel:
```php
<?php declare(strict_types=1);

namespace Vendor\Package;
```
Für die Überprüfung der Einhaltung benutzen wir den [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) eine
[Konfiguration](./../../phpcs.xml) liegt dem Projekt bei.

Für die syntaktische Korrektheit nutzen wir zur Prüfung [PHPLint](https://github.com/overtrue/phplint).

Beides wird automatisch auf GitHub geprüft, es ist also vom Vorteil, die beiden Tools vor der Erstellung eines Pull Request
lokal laufen zu lassen.

## Richtlinien

Es wird für alles, wofür ein Unit Test geschrieben werden kann, ein Unit Test geschrieben.
Solltet Ihr damit noch keine Erfahrung haben, müsst ihr zumindest zwingend ein Issues erstellen, damit ein anderer diesen
für euch erstellt.

---

# Programming Guide

In programming and that in a team, it is unspeakably important to have a consistent code style.
We use and adhere here to the accept PSR from [PHP-FIG](https://www.php-fig.org/)

## Code style

For the formatting of the code we stick to the [PSR-12](https://www.php-fig.org/psr/psr-12/) with but a small
But: Contrary to [PSR-12](https://www.php-fig.org/psr/psr-12/) we add `declare(strict_types=1);` to the first line.
line.

Example:
```php
<?php declare(strict_types=1);

namespace Vendor\Package;
```
For compliance checking we use the [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) a
[configuration](./../../phpcs.xml) is included with the project.

For syntactical correctness we use [PHPLint](https://github.com/overtrue/phplint) for checking.

Both are automatically checked on GitHub, so it is advantageous to run both tools locally before creating a pull request.

## Guidelines

A unit test will be written for everything that can be written for.
If you don't have any experience with this, you must at least create an Issues, so that someone else can create it for you.
