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

class EnumValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testEnum()
    {
        $schema = json_decode('{ "type": "string", "enum": ["red", "amber", "green"] }', true);
        $validator = new Validator($schema);

        $json = "red";
        $this->assertTrue($validator->validate($json));
        $json = "blue";
        $this->assertFalse($validator->validate($json));

        $schema = json_decode('{ "enum": ["red", "amber", "green", null, 42] }', true);
        $validator = new Validator($schema);

        $json = "red";
        $this->assertTrue($validator->validate($json));
        $json = null;
        $this->assertTrue($validator->validate($json));
        $json = 42;
        $this->assertTrue($validator->validate($json));
        $json = 0;
        $this->assertFalse($validator->validate($json));

        $schema = json_decode('{ "type": "string", "enum": ["red", "amber", "green", null] }', true);
        $validator = new Validator($schema);

        $json = "red";
        $this->assertTrue($validator->validate($json));
        $json = null;
        $this->assertFalse($validator->validate($json));
    }
}
