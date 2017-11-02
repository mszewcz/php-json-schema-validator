# php-json-schema-validator
JSON schema validator class, which provides validation of JSON files according to draft-06 specification, published on 2017-04-15.

[![Build Status](https://travis-ci.com/mszewcz/php-json-schema-validator.svg?token=SKHyUu7D9k2gxfy5aKpX&branch=develop)](https://travis-ci.com/mszewcz/php-json-schema-validator)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/7250655a51e747c6bd5d099d4240e9cf)](https://www.codacy.com?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=mszewcz/php-json-schema-validator&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/7250655a51e747c6bd5d099d4240e9cf)](https://www.codacy.com?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=mszewcz/php-json-schema-validator&amp;utm_campaign=Badge_Coverage)

## Contents
* [Installation](#Installation)
* [Usage](#Usage)
* [Supported Elements](#SupportedElements)
* [Contributing](#Contributing)
* [License](#License)


<a name="Installation"></a>
## Installation
If you use [Composer][composer] to manage the dependencies simply add a dependency on ```mszewcz/php-json-schema-validator``` to your project's composer.json file. Here is a minimal example of a composer.json:
```
{
    "require": {
        "mszewcz/php-json-schema-validator": ">=1.0"
    }
}
```
You can also clone or download this respository.

**php-json-schema-validator** meets [PSR-4][psr4] autoloading standards. If using the Composer please include its autoloader file:
```php
require_once 'vendor/autoload.php';
```
If you cloned or downloaded this repository, you will have to code your own PSR-4 style autoloader implementation.

<a name="Usage"></a>
## Usage
```php
require 'vendor/autoload.php';

try {
    $utils      = new MS\Json\Utils\Utils();
    $schema     = Utils::decode($jsonSchemaDefinition);
    $json       = Utils::decode($jsonToValidate);
    $validator  = new MS\Json\SchemaValidator\Validator($schema);
    $result     = $validator->validate($json);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```
If you don't want to use Utils class to decode JSONs, you should do that the following way:
```php
$schema = \json_decode($jsonSchemaDefinition, true);
$json   = \json_decode($jsonToValidate, true);
```

<a name="SupportedElements"></a>
## Supported elements
**php-json-schema-validator** supports validation against:
* additionalItems
* additionalProperties
* allOf
* anyOf
* const
* contains
* dependencies
* enum
* exclusiveMaximum
* exclusiveMinimum
* format (date-time, email, host, ipv4, ipv6 & uri)
* items
* maximum
* minimum
* maxItems
* maxLength
* maxProperties
* minItems
* minLength
* minProperties
* multipleOf
* not
* oneOf
* pattern
* patternProperties
* properties
* propertyNames
* required
* type
* uniqueItems

**It also supports `$ref` element, so you can use in-json definitions and references without any problems.**


<a name="Contributing"></a>
## Contributing
Contributions are welcome. Please send your contributions through GitHub pull requests 

Pull requests for bug fixes must be based on latest stable release from the ```master``` branch whereas pull requests for new features must be based on the ```developer``` branch.

Due to time constraints, I'm not always able to respond as quickly as I would like. If you feel you're waiting too long for merging your pull request please remind me here.

#### Coding standards
We follow [PSR-2][psr2] coding style and [PSR-4][psr4] autoloading standards. Be sure you're also following them before sending us your pull request.


<a name="License"></a>
## License
**php-json-schema-validator** is licensed under the MIT License - see the ```LICENSE``` file for details.

[composer]:http://getcomposer.org/
[psr2]:http://www.php-fig.org/psr/psr-2/
[psr4]:http://www.php-fig.org/psr/psr-4/
