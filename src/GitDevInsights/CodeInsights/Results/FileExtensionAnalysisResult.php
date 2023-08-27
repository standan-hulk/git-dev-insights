<?php
namespace GitDevInsights\CodeInsights\Results;

class FileExtensionAnalysisResult
{
    /**
     * @var array<string, int>
     */
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getFileExtensionLines(string $fileExtension): ?int
    {
        return $this->data[$fileExtension] ?? null;
    }

    public function addFileExtensionLines(string $fileExtension, int $lines): void
    {
        if (!isset($this->data[$fileExtension])) {
            $this->data[$fileExtension] = 0;
        }
        $this->data[$fileExtension] = (int)$this->data[$fileExtension] + $lines;
    }
}