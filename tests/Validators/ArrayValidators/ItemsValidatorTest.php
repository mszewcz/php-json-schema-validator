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

class ItemsValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testItemsSchemaObject()
    {
        $schema = json_decode('{ "type": "array", "items": { "type": "number" }}', true);
        $validator = new Validator($schema);

        $json = json_decode('[1, 2, 3, 4, 5]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1, 2, "3", 4, 5]', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('[]', true);
        $this->assertTrue($validator->validate($json));
    }

    public function testItemsSchemaArray()
    {
        $schema = json_decode('{ "type": "array", "items": [{ "type": "number" }, { "type": "string" }, 
            { "type": "string", "enum": ["Street", "Avenue", "Boulevard"] }, { "type": "string", 
            "enum": ["NW", "NE", "SW", "SE"] }]}', true);
        $validator = new Validator($schema);

        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW"]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[24, "Sussex", "Drive"]', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('["Palais de l\'Élysée"]', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('[10, "Downing", "Street"]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1600, "Pennsylvania", "Avenue", "NW", "Washington"]', true);
        $this->assertTrue($validator->validate($json));
    }
}
