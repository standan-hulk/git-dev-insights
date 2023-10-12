<?php

namespace GitDevInsights\CodeInsights\Persistence;

use GitDevInsights\CodeInsights\Model\ProgrammingFocus;
use GitDevInsights\CodeInsights\Model\ProgrammingLanguage;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class MappingLanguageFocusDataProvider {

    /**
     * @var ProgrammingFocus[]
     */
    private array $programmingFocus = [];

    /**
     * @var array<string, ProgrammingFocus>
     */
    private array $programmingLanguages = [];

    public function __construct(string $filePath) {
        $data = Yaml::parseFile($filePath);

        if (!is_array($data)) {
            throw new RuntimeException("Invalid data in file: $filePath");
        }

        foreach ($data as $feBeFocusName => $programmingLanguages) {
            $programmingFocus = new ProgrammingFocus($feBeFocusName);
            $this->programmingFocus[] = $programmingFocus;

            foreach ($programmingLanguages as $programmingLanguageName) {
                $programmingLanguage = new ProgrammingLanguage($programmingLanguageName);
                $programmingFocus->addLanguage($programmingLanguage);

                $this->programmingLanguages[strtolower($programmingLanguageName)] = $programmingFocus;
            }
        }
    }

    public function findFocusByProgrammingLanguage(string $programmingLanguage): ?ProgrammingFocus {
        return $this->programmingLanguages[strtolower($programmingLanguage)] ?? null;
    }
}