# git-dev-insights

## A simple tool to create some interesting insights about your git repo
The tool generates statistics over a customizable time period from your Git repository, providing insights into used programming languages and trends from the past.
The programming languages can be freely configured based on their file extensions.

### Setup
`composer install`



### PHPStan

#### 1. run phpstan
`composer phpstan`

#### 2. generate baseline
`composer phpstan-baseline`


# Tool Usage

1. Generate config file in `project-configs/projectname.yaml`.
2. Create an `analyse-yourproject.sh` with your project config file. (see examples)
3. Run `sh analyse-yourproject.sh` 

# example graph
![Graph Example](https://raw.githubusercontent.com/standan-hulk/git-dev-insights/master/files/graph-example.png)