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

class AnyOfValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testUnknownType()
    {
        $schema = json_decode('{ "anyOf": [ { "type": "string" }, { "type": "number" } ]}', true);
        $validator = new Validator($schema);

        $json = "short";
        $this->assertTrue($validator->validate($json));
        $json = 42;
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "not": "string nor number" }', true);
        $this->assertFalse($validator->validate($json));
    }
}
