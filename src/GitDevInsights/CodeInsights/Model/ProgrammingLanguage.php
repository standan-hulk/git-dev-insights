<?php

namespace gitDevInsights\CodeInsights\Model;

final class ProgrammingLanguage {
    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }
}