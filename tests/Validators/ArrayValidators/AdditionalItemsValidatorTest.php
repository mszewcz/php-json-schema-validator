<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace Validators\ArrayValidators;


use MS\Json\SchemaValidator\Validator;
use PHPUnit\Framework\TestCase;

class AdditionalItemsValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testAdditionalItemsSchemaBoolean()
    {
        $schema = json_decode('{ "type": "array", "items": [{ "type": "number" }, { "type": "string" },  
            { "type": "string", "enum": ["Street", "Avenue", "Boulevard"] }, 
            { "type": "string", "enum": ["NW", "NE", "SW", "SE"] }], "additionalItems": false }', true);
        $validator = new Validator($schema);

        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW"]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1600, "Pennsylvania", "Avenue"]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW", "Washington"]', true);
        $this->assertFalse($validator->validate($json));
    }

    public function testAdditionalItemsSchemaObject()
    {
        $schema = json_decode('{ "type": "array", "items": [{ "type": "number" }, { "type": "string" }, 
            { "type": "string", "enum": ["Street", "Avenue", "Boulevard"] }, 
            { "type": "string", "enum": ["NW", "NE", "SW", "SE"] }], "additionalItems": { "type": "string" }}', true);
        $validator = new Validator($schema);

        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW", "Washington"]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW", 3]', true);
        $this->assertFalse($validator->validate($json));
    }

    public function testAdditionalItemsSchemaArray()
    {
        $schema = json_decode('{ "type": "array", "items": [{ "type": "number" }, { "type": "string" }, 
            { "type": "string", "enum": ["Street", "Avenue", "Boulevard"] }, 
            { "type": "string", "enum": ["NW", "NE", "SW", "SE"] }], "additionalItems": [{ "type": "number" }, 
            { "type": "string" }]}', true);
        $validator = new Validator($schema);

        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW", 3]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW", 3, "SE"]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW", 3, "SE", "XX"]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW", 3, "SE", 3]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW", "3"]', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW", 3, 5]', true);
        $this->assertFalse($validator->validate($json));
    }
}
