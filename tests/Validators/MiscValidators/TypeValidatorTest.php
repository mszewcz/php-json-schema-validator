<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace Validators\MiscValidators;


use MS\Json\SchemaValidator\Validator;
use PHPUnit\Framework\TestCase;

class TypeValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testTypeBoolean()
    {
        $schema = json_decode('{ "type": "boolean" }', true);
        $validator = new Validator($schema);

        $json = true;
        $this->assertTrue($validator->validate($json));
        $json = false;
        $this->assertTrue($validator->validate($json));
        $json = 'true';
        $this->assertFalse($validator->validate($json));
        $json = 0;
        $this->assertFalse($validator->validate($json));
    }

    public function testTypeNumeric()
    {
        $schema = json_decode('{ "type": "number" }', true);
        $validator = new Validator($schema);

        $json = 41;
        $this->assertTrue($validator->validate($json));
        $json = -1;
        $this->assertTrue($validator->validate($json));
        $json = 3.1415926;
        $this->assertTrue($validator->validate($json));
        $json = '42';
        $this->assertFalse($validator->validate($json));
    }

    public function testTypeInteger()
    {
        $schema = json_decode('{ "type": "integer" }', true);
        $validator = new Validator($schema);

        $json = 41;
        $this->assertTrue($validator->validate($json));
        $json = -1;
        $this->assertTrue($validator->validate($json));
        $json = 3.1415926;
        $this->assertFalse($validator->validate($json));
        $json = '42';
        $this->assertFalse($validator->validate($json));
    }

    public function testTypeFloat()
    {
        $schema = json_decode('{ "type": "float" }', true);
        $validator = new Validator($schema);

        $json = 41;
        $this->assertFalse($validator->validate($json));
        $json = -1;
        $this->assertFalse($validator->validate($json));
        $json = 3.1415926;
        $this->assertTrue($validator->validate($json));
        $json = '42';
        $this->assertFalse($validator->validate($json));
    }

    public function testTypeString()
    {
        $schema = json_decode('{ "type": "string" }', true);
        $validator = new Validator($schema);

        $json = 'This is a string';
        $this->assertTrue($validator->validate($json));
        $json = 'Déjà vu';
        $this->assertTrue($validator->validate($json));
        $json = '';
        $this->assertTrue($validator->validate($json));
        $json = '42';
        $this->assertTrue($validator->validate($json));
        $json = 42;
        $this->assertFalse($validator->validate($json));
    }

    public function testTypeArray()
    {
        $schema = json_decode('{ "type": "array" }', true);
        $validator = new Validator($schema);

        $json = json_decode('[1, 2, 3, 4, 5]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[3, "different", { "types" : "of values" }]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{"Not": "an array"}', true);
        $this->assertFalse($validator->validate($json));
        $json = "Not an array";
        $this->assertFalse($validator->validate($json));
    }

    public function testTypeObject()
    {
        $schema = json_decode('{ "type": "object" }', true);
        $validator = new Validator($schema);

        $json = json_decode('{ "key" : "value", "another_key" : "another_value" }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "Sun" : 1.9891e30, "Jupiter" : 1.8986e27, "Saturn" : 5.6846e26, "Neptune" : 10.243e25, 
            "Uranus" : 8.6810e25, "Earth" : 5.9736e24, "Venus" : 4.8685e24, "Mars" : 6.4185e23, "Mercury" : 3.3022e23, 
            "Moon" : 7.349e22, "Pluto" : 1.25e22 }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ 0.01 : "cm" 1 : "m", 1000 : "km" }', true);
        $this->assertFalse($validator->validate($json));
        $json = "Not an object";
        $this->assertFalse($validator->validate($json));
        $json = json_decode('["An", "array", "not", "an", "object"]', true);
        $this->assertFalse($validator->validate($json));
    }

    public function testTypeNull()
    {
        $schema = json_decode('{ "type": "null" }', true);
        $validator = new Validator($schema);

        $json = null;
        $this->assertTrue($validator->validate($json));
        $json = false;
        $this->assertFalse($validator->validate($json));
        $json = '';
        $this->assertFalse($validator->validate($json));
        $json = 0;
        $this->assertFalse($validator->validate($json));
    }

    public function testTypeUnknown()
    {
        $schema = json_decode('{ "type": "xxx" }', true);
        $validator = new Validator($schema);

        $json = json_decode('[1, 2, 3, 4, 5]', true);
        $this->assertFalse($validator->validate($json));
    }
}
