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

class PatternPropertiesValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testPatternProperties()
    {
        $schema = json_decode('{ "type": "object", "patternProperties": { "^S_": { "type": "string" }, "^I_": { "type": "integer" } }, "additionalProperties": false }', true);
        $validator = new Validator($schema);

        $json = json_decode('{ "S_25": "This is a string" }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "I_0": 42 }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "S_0": 42 }', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('{ "I_42": "This is a string" }', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('{ "keyword": "value" }', true);
        $this->assertFalse($validator->validate($json));

        $schema = json_decode('{ "type": "object", "properties": { "builtin": { "type": "number" } }, "patternProperties": { "^S_": { "type": "string" }, "^I_": { "type": "integer" } }, "additionalProperties": { "type": "string" } }', true);
        $validator = new Validator($schema);

        $json = json_decode('{ "builtin": 42 }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "keyword": "value" }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "keyword": 42 }', true);
        $this->assertFalse($validator->validate($json));
    }
}
