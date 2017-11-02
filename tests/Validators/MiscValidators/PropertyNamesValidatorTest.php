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

class PropertyNamesValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testUnknownType()
    {
        $schema = json_decode('{
    "type": "object",
    "anyOf": [
        {"$ref": "#/definitions/foo"},
        {"$ref": "#/definitions/bar"}
    ],
    "propertyNames": { "enum": ["foo", "bar"] },
    "definitions": {
        "foo": {
            "properties": {
                "foo": {"type": "string"}
            }
        },
        "bar": {
            "properties": {
                "bar": {"type": "number"}
            }
        }
    }
}', true);

        $validator = new Validator($schema);

        $json = json_decode('{"foo":"aaa"}', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{"foo":"aaa", "bar":4}', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{"foo":"aaa", "bar":4, "baz":5}', true);
        $this->assertFalse($validator->validate($json));
    }
}
