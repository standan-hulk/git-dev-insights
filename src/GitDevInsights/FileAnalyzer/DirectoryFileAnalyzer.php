<?php

namespace GitDevInsights\FileAnalyzer;

final class DirectoryFileAnalyzer {

    /**
     * @var string[]
     */
    private array $allowedExtensions;

    /**
     * @param string[] $allowedExtensions
     */
    public function __construct(array $allowedExtensions) {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * @return string[]
     */
    public function searchFilesInDirectory(string $directory): array {
        $foundFiles = [];

        foreach ($this->allowedExtensions as $extension) {
            $pattern = rtrim($directory, '/') . '/*.' . ltrim($extension, '.');
            $files = glob($pattern);

            if ($files !== false) {
                foreach ($files as $file) {
                    $foundFiles[] = $file;
                }
            }
        }

        return $foundFiles;
    }
}
