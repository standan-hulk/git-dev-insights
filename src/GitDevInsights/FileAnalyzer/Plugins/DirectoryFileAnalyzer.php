<?php

namespace GitDevInsights\FileAnalyzer\Plugins;

use DirectoryIterator;

final class DirectoryFileAnalyzer {

    /**
     * @var string[]
     */
    private array $allowedExtensions;

    /**
     * @param string[] $allowedExtensions
     */
    public function __construct(array $allowedExtensions) {
        $this->allowedExtensions = array_map('strtolower', $allowedExtensions);
    }

    /**
     * @return string[]
     */
    public function searchFilesInDirectory(string $path): array
    {
        $result = [];

        $dir = new DirectoryIterator($path);

        foreach ($dir as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            $filePath = $fileInfo->getPathname();

            if ($fileInfo->isDir()) {
                $files = $this->searchFilesInDirectory($filePath);
                if (count($files) > 0) {
                    $result = array_merge($result, $files);
                }
            } elseif ($fileInfo->isFile()) {
                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));


                if($extension === '') {
                    continue;
                }

                if(in_array($extension, $this->allowedExtensions, true)) {
                    $result[] = $filePath;
                }
            }
        }

        return $result;
    }
}
