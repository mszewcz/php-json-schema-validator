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

class FormatValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testFormatDateTime()
    {
        $schema = json_decode('{ "type": "string", "format": "date-time" }', true);
        $validator = new Validator($schema);

        $json = '2017-04-09T13:11:39Z';
        $this->assertTrue($validator->validate($json));
        $json = '2017-04-09T23:59:60Z';
        $this->assertTrue($validator->validate($json));
        $json = '2017-04-09T13:11:39.15Z';
        $this->assertTrue($validator->validate($json));
        $json = '2017-04-09T13:11:39+01:00';
        $this->assertTrue($validator->validate($json));
        $json = '2017-04-09T13:11:39-01:00';
        $this->assertTrue($validator->validate($json));
        $json = '2017-04-09T13:11:39.15-01:00';
        $this->assertTrue($validator->validate($json));
        $json = '2017-10-09T13:11:39';
        $this->assertFalse($validator->validate($json));
        $json = '2017-13-09T13:11:39Z';
        $this->assertFalse($validator->validate($json));
        $json = '2017-04-31T13:11:39Z';
        $this->assertFalse($validator->validate($json));
        $json = '2017-04-32T13:11:39Z';
        $this->assertFalse($validator->validate($json));
        $json = '2017-04-15T24:11:39Z';
        $this->assertFalse($validator->validate($json));
        $json = '2017-04-15T13:60:39Z';
        $this->assertFalse($validator->validate($json));
        $json = '2017-04-15T13:59:61Z';
        $this->assertFalse($validator->validate($json));
        $json = '2017-04-15T13:59:30+24:00';
        $this->assertFalse($validator->validate($json));
        $json = '2017-04-15T13:59:30+08:60';
        $this->assertFalse($validator->validate($json));
    }

    public function testFormatEmail()
    {
        $schema = json_decode('{ "type": "string", "format": "email" }', true);
        $validator = new Validator($schema);

        $json = 'ms@msworks.pl';
        $this->assertTrue($validator->validate($json));
        $json = 'ms@msworks';
        $this->assertFalse($validator->validate($json));
        $json = 'msworks.pl';
        $this->assertFalse($validator->validate($json));
    }

    public function testFormatHost()
    {
        $schema = json_decode('{ "type": "string", "format": "hostname" }', true);
        $validator = new Validator($schema);

        $json = 'msworks.pl';
        $this->assertTrue($validator->validate($json));
        $json = 'localhost';
        $this->assertTrue($validator->validate($json));
        $json = '192.168.0.1';
        $this->assertTrue($validator->validate($json));
        $json = 'mandrill._domainkey.mailchimp.com';
        $this->assertFalse($validator->validate($json));
        $json = 'http://a_.bc.com';
        $this->assertFalse($validator->validate($json));
        $json = 'toolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolong.com';
        $this->assertFalse($validator->validate($json));
        $json = '[2001:0db8:0000:85a3:0000:0000:ac1f:8001]';
        $this->assertFalse($validator->validate($json));
    }

    public function testFormatIPv4()
    {
        $schema = json_decode('{ "type": "string", "format": "ipv4" }', true);
        $validator = new Validator($schema);

        $json = '192.168.0.1';
        $this->assertTrue($validator->validate($json));
        $json = '192.168.0';
        $this->assertFalse($validator->validate($json));
        $json = '192.168.0.1.1';
        $this->assertFalse($validator->validate($json));
        $json = '192.168.0.0/8';
        $this->assertFalse($validator->validate($json));
    }

    public function testFormatIPv6()
    {
        $schema = json_decode('{ "type": "string", "format": "ipv6" }', true);
        $validator = new Validator($schema);

        $json = '2001:0db8:85a3:08d3:1319:8a2e:0370:7334';
        $this->assertTrue($validator->validate($json));
        $json = '192.168.0.1';
        $this->assertFalse($validator->validate($json));
        $json = '2001:0db8:85a3:08d3:1319:8a2e:';
        $this->assertFalse($validator->validate($json));
        $json = '2001:0db8:85a3:08d3:1319:8a2e:0370:7334:ffff';
        $this->assertFalse($validator->validate($json));
    }

    public function testFormatUri()
    {
        $schema = json_decode('{ "type": "string", "format": "uri" }', true);
        $validator = new Validator($schema);

        $json = 'http://msworks.pl/';
        $this->assertTrue($validator->validate($json));
        $json = 'https://msworks.pl/some-page/index.php?a=1';
        $this->assertTrue($validator->validate($json));
        $json = 'msworks.pl';
        $this->assertFalse($validator->validate($json));
        $json = 'http://subdomain._msworks.pl/';
        $this->assertFalse($validator->validate($json));
    }

    public function testFormatUnknown()
    {
        $schema = json_decode('{ "type": "string", "format": "unknown" }', true);
        $validator = new Validator($schema);

        $json = '';
        $this->assertTrue($validator->validate($json));
    }
}
