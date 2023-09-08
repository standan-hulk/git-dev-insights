<?php

namespace GitDevInsights\CodeInsights\Persistence;

use GitDevInsights\CodeInsights\Model\ProgrammingFocus;
use GitDevInsights\CodeInsights\Model\ProgrammingLanguage;
use Symfony\Component\Yaml\Yaml;

class MappingLanguageFocusDataProvider {

    /**
     * @var ProgrammingFocus[]
     */
    private array $programmingFocus = [];

    /**
     * @var array<string, ProgrammingLanguage>
     */
    private array $programmingLanguages = [];

    public function __construct(string $filePath) {
        $data = Yaml::parseFile($filePath);

        foreach ($data as $feBeFocusName => $programmingLanguages) {
            $programmingFocus = new ProgrammingFocus($feBeFocusName);
            $this->programmingFocus[] = $programmingFocus;

            foreach ($programmingLanguages as $programmingLanguageName) {
                $programmingLanguage = new ProgrammingLanguage($programmingLanguageName);
                $programmingFocus->addLanguage($programmingLanguage);

                $this->programmingLanguages[(string)strtolower($programmingLanguageName)] = $programmingFocus;
            }
        }
    }

    public function findFocusByProgrammingLanguage(string $programmingLanguage): ?ProgrammingFocus {
        return $this->programmingLanguages[strtolower($programmingLanguage)] ?? null;
    }

    /**
     * @return ProgrammingFocus[]
     */
    public function getProgrammingFocus(): array {
        return $this->programmingFocus;
    }

    /**
     * @return array<string, ProgrammingLanguage>
     */
    public function getProgrammingLanguages(): array {
        return $this->programmingLanguages;
    }
}