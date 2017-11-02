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

class AllOfValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testUnknownType()
    {
        $schema = json_decode('{ "allOf": [ { "type": "string" }, { "maxLength": 5 } ]}', true);
        $validator = new Validator($schema);

        $json = "short";
        $this->assertTrue($validator->validate($json));
        $json = "too long";
        $this->assertFalse($validator->validate($json));
    }
}
