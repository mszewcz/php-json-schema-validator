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

class MultipleOfValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testMultipleOf()
    {
        $schema = json_decode('{ "type": "number", "multipleOf": 1.0 }', true);
        $validator = new Validator($schema);

        $json = 42;
        $this->assertTrue($validator->validate($json));
        $json = 42.0;
        $this->assertTrue($validator->validate($json));
        $json = 3.14156926;
        $this->assertFalse($validator->validate($json));
    }
}
