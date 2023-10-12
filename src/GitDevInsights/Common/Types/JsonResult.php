<?php

namespace GitDevInsights\Common\Types;

final class JsonResult
{
    private array $jsonData;

    public function __construct(array $jsonData)
    {
        $this->jsonData = $jsonData;
    }

    public function toJsonString(): string
    {
        return json_encode($this->jsonData, JSON_THROW_ON_ERROR);
    }
}