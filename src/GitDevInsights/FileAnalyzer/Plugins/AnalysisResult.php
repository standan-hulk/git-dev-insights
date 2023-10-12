<?php

namespace GitDevInsights\FileAnalyzer\Plugins;

final class AnalysisResult
{

    /**
     * @var int[]
     */
    private array $values;

    /**
     * @param int[] $values
     */

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function add(AnalysisResult $otherResult): void
    {
        foreach ($otherResult->values as $key => $value) {
            // create key, if not present
            if (!isset($this->values[$key])) {
                $this->values[$key] = 0;
            }

            $this->values[$key] += $value;
        }
    }
}
