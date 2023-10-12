<?php

namespace GitDevInsights\CodeInsights\Persistence;

use GitDevInsights\CodeInsights\Model\ProgrammingLanguage;
use GitDevInsights\CodeInsights\Model\ProgrammingLanguageFileExt;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class MappingLanguageDataProvider {
    /**
     * @var ProgrammingLanguageFileExt[]
     */
    private array $fileExtensions = [];

    public function __construct(string $filePath) {
        $data = Yaml::parseFile($filePath);

        if (!is_array($data)) {
            throw new RuntimeException("Invalid data in file: $filePath");
        }

        foreach ($data as $languageName => $extensions) {
            $programmingLanguage = new ProgrammingLanguage($languageName);
            $this->programmingLanguages[] = $programmingLanguage;

            foreach ($extensions as $extension) {
                $fileExtension = new ProgrammingLanguageFileExt($extension, $programmingLanguage);
                // Caution: A file extension MUST belong to one programming language by now
                $programmingLanguage->addExtension($fileExtension);

                $this->fileExtensions[$extension] = $fileExtension;
            }
        }
    }

    public function findLanguageByExtension(string $fileExt): ?ProgrammingLanguageFileExt {
        return $this->fileExtensions[strtolower($fileExt)] ?? null;
    }

    /**
     * @return ProgrammingLanguageFileExt[]
     */
    public function getFileExtensions(): array {
        return $this->fileExtensions;
    }

    /**
     * @return string[]
     */
    public function getFileExtensionsStringList() : array {
        $result = [];

        foreach($this->fileExtensions as $extension) {
            $result[] = $extension->name;
        }

        return $result;
    }
}