<?php

namespace GitDevInsights\CodeInsights\Output;

class LanguageChartHTMLFileGenerator
{
    private $jsonData;

    public function __construct(array $jsonData)
    {
        $this->jsonData = $jsonData;
    }

    public function renderChartOutput()
    {
        // Daten in ein für Chart.js geeignetes Format umwandeln
        $labels = [];
        $datasets = [];

        $colors = ["rgb(255, 99, 132)", "rgb(75, 192, 192)", "rgb(255, 205, 86)", "rgb(54, 162, 235)", "rgb(153, 102, 255)"];
        $i = 0;

        foreach ($this->jsonData["language-global-data"] as $date => $values) {
            $labels[] = $date;

            foreach ($values as $language => $count) {
                $dataset = [
                    "label" => $language,
                    "data" => [],
                    "fill" => false,
                    "borderColor" => $colors[$i],
                    "tension" => 0.1,
                ];

                foreach ($this->jsonData["language-global-data"] as $date => $langData) {
                    $dataset["data"][] = $langData[$language];
                }

                $datasets[] = $dataset;

                $i++;
            }
        }

        // Das Diagramm erstellen
        $html = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Line Chart Example</title>
        <!-- Einbinden der Chart.js-Bibliothek -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
    <div style="width: 80%; margin: 0 auto;">
        <!-- Das HTML-Canvas-Element, in dem das Diagramm gerendert wird -->
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>

    <script>
        var labels = ' . json_encode($labels) . ';
        var datasets = ' . json_encode($datasets) . ';

        // Das Diagramm erstellen
        var ctx = document.getElementById("myChart").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: datasets,
            },
            options: {
                scales: {
                    x: {
                        type: "category",
                        title: {
                            display: true,
                            text: "Datum"
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: "Anzahl Zeilen Code"
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom"
                    }
                }
            }
        });
    </script>
    </body>
    </html>';

        return $html;
    }


    public function writeChartOutputToFile($fileName)
    {
        $html = $this->renderChartOutput();
        file_put_contents($fileName, $html);
        return 'Die HTML-Datei "' . $fileName . '" wurde erfolgreich erstellt.';
    }
}