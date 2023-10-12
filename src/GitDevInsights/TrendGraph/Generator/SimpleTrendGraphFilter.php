<?php

namespace GitDevInsights\TrendGraph\Generator;

class SimpleTrendGraphFilter
{
    private string $filterKey;


    public function __construct(string $filterKey)
    {
        $this->filterKey = $filterKey;
    }

    /**
     * @return array<int|string, int>
     */
    private function getValuesSetForOutput(array $jsonData): array {
        if ($jsonData[$this->filterKey] === []) {
            return [];
        }

        $valuesSet = [];

        foreach ($jsonData[$this->filterKey] as $values) {
            foreach ($values as $key => $value) {

                if ((int)$value > 0) {
                    $valuesSet[$key] = 1;
                }
            }
        }

        return $valuesSet;
    }

    public function filterDataByValuesSet(array $jsonData): array {
        $filteredData = [];
        $result = [];

        $valuesSet = $this->getValuesSetForOutput($jsonData);

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