# Behat 3 JSON formatter

Behat 3 extension for generating JSON results

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
            "emuse/behat-html-formatter": "0.1.*",
        },
        "minimum-stability": "dev",
        "config": {
            "bin-dir": "bin/"
        }
    }

# Basic usage

Activate the extension by specifying its class in your behat.yml:

# behat.yml

    default:
      suites:
        ... # All your awesome suites come here
    
      formatters:
        json_formatter:
        
      extensions:
        ggturkalanov\Behat3JsonExtension:
            prettify: true
            file_name: result.json