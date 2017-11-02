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

class OneOfValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testUnknownType()
    {
        $schema = json_decode('{"oneOf":[{"type":"number","multipleOf":5},{"type":"number","multipleOf":3}]}', true);
        $validator = new Validator($schema);

        $json = 10;
        $this->assertTrue($validator->validate($json));
        $json = 9;
        $this->assertTrue($validator->validate($json));
        $json = 2;
        $this->assertFalse($validator->validate($json));
        $json = 15;
        $this->assertFalse($validator->validate($json));
    }
}
