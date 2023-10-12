<?php

namespace GitDevInsights\Common\Types;


/**
 * @immmutable
 */
final class JsonResult
{
    public string $jsonData;

    public function __construct(string $jsonData)
    {
        $this->jsonData = $jsonData;
    }
}