<?php

use GitDevInsights\CodeInsights\Service\CodeInsightsService;

require_once('vendor/autoload.php');

$codeInsightsService = new CodeInsightsService('project-configs/phpmyadmin.yaml');
$codeInsightsService->analyze();