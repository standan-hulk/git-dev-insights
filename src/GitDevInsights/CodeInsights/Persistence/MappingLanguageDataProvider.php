<?php

namespace GitDevInsights\CodeInsights\Persistence;

use gitDevInsights\CodeInsights\Model\ProgrammingLanguage;
use gitDevInsights\CodeInsights\Model\ProgrammingLanguageFileExt;
use Symfony\Component\Yaml\Yaml;

class MappingLanguageDataProvider {

    /**
     * @var ProgrammingLanguage[]
     */
    private array $programmingLanguages = [];

    /**
     * @var ProgrammingLanguageFileExt[]
     */
    private array $fileExtensions = [];

    public function __construct($filePath) {
        $data = Yaml::parseFile($filePath);

        foreach ($data as $languageName => $extensions) {
            $programmingLanguage = new ProgrammingLanguage($languageName);
            $this->programmingLanguages[] = $programmingLanguage;

            foreach ($extensions as $extension) {
                // Caution: A file extension MUST belong to one programming language by now
                $this->fileExtensions[$extension] = new ProgrammingLanguageFileExt($extension, $programmingLanguage);
            }
        }
    }

    public function findLanguageByExtension(string $fileExt): ?ProgrammingLanguageFileExt {
        return $this->fileExtensions[$fileExt] ?? null;
    }

    /**
     * @return ProgrammingLanguageFileExt[]
     */
    public function findExtensionsByLanguage(ProgrammingLanguage $language): array{
        $extensions = [];

        foreach ($this->fileExtensions as $extension => $programmingLanguage) {
            if ($programmingLanguage === $language) {
                $extensions[] = $extension;
            }
        }

        return $extensions;
    }

    public function getProgrammingLanguages() {
        return $this->programmingLanguages;
    }
}