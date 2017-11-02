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

class MaxPropertiesValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testMinProperties()
    {
        $schema = json_decode('{ "type": "object", "maxProperties": 3}', true);
        $validator = new Validator($schema);

        $json = json_decode('{}', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "a": 1 }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "a": 1, "b": 2 }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "a": 1, "b": 2, "c": 3 }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "a": 1, "b": 2, "c": 3, "d": 4 }', true);
        $this->assertFalse($validator->validate($json));
    }
}
