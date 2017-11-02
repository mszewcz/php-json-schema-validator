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

class ContainsValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testContains()
    {
        $schema = json_decode('{ "type": "array", "contains": { "test": "xxx" } }', true);
        $validator = new Validator($schema);

        $json = json_decode('[1, 2, "test", { "test": "xxx" }]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1, 2, "test"]', true);
        $this->assertFalse($validator->validate($json));
    }
}
