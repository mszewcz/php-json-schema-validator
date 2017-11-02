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

class UniqueItemsValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testItemsUniqueness()
    {
        $schema = json_decode('{ "type": "array", "uniqueItems": true }', true);
        $validator = new Validator($schema);

        $json = json_decode('[1,2,3,4,5]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[12,3,3,5]', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('[]', true);
        $this->assertTrue($validator->validate($json));
    }
}
