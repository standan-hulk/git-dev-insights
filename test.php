<?php

require_once('vendor/autoload.php');

// Verzeichnis, in dem das Repository geklont wurde
$repositoryPath = 'source-repo/data/Star-Confederation';

// Array der unterstützten Dateierweiterungen
$supportedExtensions = ['php', 'css', 'html', 'js', 'ts'];

// Array zur Speicherung der Code-Verteilung
$codeDistribution = array_fill_keys($supportedExtensions, 0);

// Funktion zur rekursiven Verarbeitung von Dateien und Verzeichnissen
function processDirectory($path, &$codeDistribution, $supportedExtensions)
{
    $dir = new DirectoryIterator($path);

    foreach ($dir as $fileInfo) {
        if ($fileInfo->isDot()) {
            continue;
        }

        $filePath = $fileInfo->getPathname();

        if ($fileInfo->isDir()) {
            processDirectory($filePath, $codeDistribution, $supportedExtensions);
        } elseif ($fileInfo->isFile()) {
            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

            if (in_array($fileExtension, $supportedExtensions)) {
                $lines = file($filePath);
                $codeDistribution[$fileExtension] += count($lines);
            }
        }
    }
}

// Rufen Sie die Verarbeitungsfunktion auf dem Repository-Verzeichnis auf
processDirectory($repositoryPath, $codeDistribution, $supportedExtensions);

// Ausgabe der Code-Verteilung
print_r($codeDistribution);