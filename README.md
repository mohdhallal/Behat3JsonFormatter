# Behat 3 JSON formatter

Behat 3 extension for generating JSON results


![Example of JSON formatter](example.png = 600x1100 )


# How ?
* The tool can be installed easily with composer.
* Defining the formatter in the behat.yml file
* Modifying the settings in the behat.ymlfile

# Installation
This extension requires:

* PHP 5.3.x or higher
* Behat 3.x or higher

# Through composer

The easiest way to keep your suite updated is to use [Composer](https://getcomposer.org/):

**Install with composer:**

        composer require gturkalanov/behat-3-json-formatter
Install with **composer.json:**

    {
        "require": {
            "behat/behat": "3.*@stable",
            "gturkalanov/behat-3-json-formatter": "dev-master",
        },
        "minimum-stability": "dev",
        "config": {
            "bin-dir": "bin/"
        }
    }

# Basic usage

Activate the extension by specifying its class in your **behat.yml**:

    default:
      suites:
        ... # All your awesome suites come here
    
      formatters:
        json_formatter:
        
      extensions:
        gturkalanov\Behat3JsonExtension:
            prettify: true
            
# Extension configuration

* **prettify** - Define if the output of the console is one liner or prettified json
* **file_name** - If this parameter is set there will be no console output but the result will be saved in json file with name - your input here
* **path** - There is a default path set **build/json_results** . You can change it here.