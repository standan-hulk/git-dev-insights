<?php

namespace GitDevInsights\CodeInsights\Persistence;

use GitDevInsights\CodeInsights\Model\ProgrammingLanguage;
use GitDevInsights\CodeInsights\Model\ProgrammingLanguageFileExt;
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

    public function __construct(string $filePath) {
        $data = Yaml::parseFile($filePath);

        foreach ($data as $languageName => $extensions) {
            $programmingLanguage = new ProgrammingLanguage($languageName);
            $this->programmingLanguages[] = $programmingLanguage;

            foreach ($extensions as $extension) {
                // Caution: A file extension MUST belong to one programming language by now
                $programmingLanguage->addExtension(new ProgrammingLanguageFileExt($extension, $programmingLanguage));
            }
        }
    }

    public function findLanguageByExtension(string $fileExt): ?ProgrammingLanguageFileExt {
        return $this->fileExtensions[$fileExt] ?? null;
    }

    /**
     * @return ProgrammingLanguage[]
     */
    public function getProgrammingLanguages(): array {
        return $this->programmingLanguages;
    }

    /**
     * @return ProgrammingLanguageFileExt[]
     */
    public function getFileExtensions(): array {
        return $this->fileExtensions;
    }
}