# git-dev-insights

## A simple tool to create some interesting insights about your git repo
The tool generates statistics over a customizable time period from your Git repository, providing insights into used programming languages and trends from the past.
The programming languages can be freely configured based on their file extensions.

## Setup
Run `composer install`

### PHPStan

#### 1. phpstan static code analysis
Run `composer phpstan`

#### 2. generate baseline
Run `composer phpstan-baseline`

## Tool Usage

1. Copy a config folder in `examples/` and name it for your git repo, e.g. `/examples/your-project`
2. Setup `analyse.sh` in your project folder.
3. Setup `config.yaml` in your project folder.
4. Run `sh projects/yourproject/analyse.sh` 

## example graph
![Graph Example](https://raw.githubusercontent.com/standan-hulk/git-dev-insights/master/files/graph-example.png)