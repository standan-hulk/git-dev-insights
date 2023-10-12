<?php

namespace GitDevInsights\TrendGraph\Generator;

class SimpleTrendGraphFilter
{
    private array $jsonData;

    private string $filterKey;


    public function __construct(string $filterKey, array $jsonData)
    {
        $this->filterKey = $filterKey;
        $this->jsonData = $this->filterDataByValuesSet($jsonData);
    }

    /**
     * @return array<int|string, int>
     */
    private function getValuesSetForOutput(): array {
        $dates = $this->jsonData[$this->filterKey];

        $valuesSet = [];

        if ($dates !== []) {
            foreach ($dates as $values) {
                foreach ($values as $key => $value) {
                    if ((int)$value > 0) {
                        $valuesSet[$key] = 1;
                    }
                }
            }
        }
        return $valuesSet;
    }

    private function filterDataByValuesSet(array $jsonData): array {
        $filteredData = [];
        $result = [];
        $valuesSet = $this->getValuesSetForOutput();

        $keyTemplate = array_combine(array_keys($valuesSet), array_fill(0, count($valuesSet), 0));

        foreach ($jsonData[$this->filterKey] as $date => $values) {
            $filteredValues = $keyTemplate;

            foreach ($values as $key => $value) {
                if (isset($valuesSet[$key]) && (int)$value > 0) {
                    $filteredValues[$key] = $value;
                }
            }

            $filteredData[$date] = $filteredValues;
        }

        $result[$this->filterKey] = $filteredData;
        return $result;
    }
}