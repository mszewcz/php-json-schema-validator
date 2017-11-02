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

class NotValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testUnknownType()
    {
        $schema = json_decode('{ "not": { "type": "string" } }', true);
        $validator = new Validator($schema);

        $json = 10;
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "key": "value" }', true);
        $this->assertTrue($validator->validate($json));
        $json = "String";
        $this->assertFalse($validator->validate($json));
    }
}
