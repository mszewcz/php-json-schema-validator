<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\Json\SchemaValidator\Validator;
use PHPUnit\Framework\TestCase;

class PropertiesValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testProperties()
    {
        $schema = json_decode('{ "type": "object", "properties": { "number": { "type": "number" }, 
            "street_name": { "type": "string" }, "street_type": { "type": "string", 
            "enum": ["Street", "Avenue", "Boulevard"] } } }', true);
        $validator = new Validator($schema);

        $json = json_decode('{ "number": 1600, "street_name": "Pennsylvania", "street_type": "Avenue" }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "number": "1600", "street_name": "Pennsylvania", "street_type": "Avenue" }', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('{ "number": 1600, "street_name": "Pennsylvania" }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "number": 1600, "street_name": "Pennsylvania", "street_type": "Avenue", 
            "direction": "NW" }', true);
        $this->assertTrue($validator->validate($json));
    }
}
