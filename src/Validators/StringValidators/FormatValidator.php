<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator\Validators\StringValidators;


use MS\Json\SchemaValidator\Schema\Resolver;
use MS\Json\SchemaValidator\Validators\ValidatorInterface;

class FormatValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $schema;
    /**
     * @var array
     */
    private $rootSchema;

    /**
     * FormatValidator constructor.
     *
     * @param array $schema
     * @param array $rootSchema
     * @throws \Exception
     */
    public function __construct(array $schema, array $rootSchema)
    {
        $this->schema = (new Resolver($schema, $rootSchema))->resolve();
        $this->rootSchema = $rootSchema;
    }

    /**
     * Validates subject against format
     *
     * @param $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        switch ($this->schema['format']) {
            case 'date-time':
                $validator = new FormatDateTimeValidator($this->schema, $this->rootSchema);
                return $validator->validate($subject);
                break;
            case 'email':
                return $this->validateFormatEmail($subject);
                break;
            case 'hostname':
                return $this->validateFormatHost($subject);
                break;
            case 'ipv4':
                return $this->validateFormatIPv4($subject);
                break;
            case 'ipv6':
                return $this->validateFormatIPv6($subject);
                break;
            case 'uri':
                return $this->validateFormatUri($subject);
                break;
        }
        return true;
    }

    /**
     * 'email' format validation
     *
     * @param string $subject
     * @return bool
     */
    private function validateFormatEmail(string $subject): bool
    {
        return \filter_var($subject, \FILTER_VALIDATE_EMAIL, \FILTER_FLAG_EMAIL_UNICODE) !== false;
    }

    /**
     * 'host' format validation
     *
     * @param string $subject
     * @return bool
     */
    private function validateFormatHost(string $subject): bool
    {
        return \filter_var($subject, \FILTER_VALIDATE_DOMAIN, \FILTER_FLAG_HOSTNAME) !== false;
    }

    /**
     * 'ipv4' format validation
     *
     * @param string $subject
     * @return bool
     */
    private function validateFormatIPv4(string $subject): bool
    {
        return \filter_var($subject, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4) !== false;
    }

    /**
     * 'ipv6' format validation
     *
     * @param string $subject
     * @return bool
     */
    private function validateFormatIPv6(string $subject): bool
    {
        return \filter_var($subject, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6) !== false;
    }

    /**
     * 'uri' format validation
     *
     * @param string $subject
     * @return bool
     */
    private function validateFormatUri(string $subject): bool
    {
        return \filter_var($subject, \FILTER_VALIDATE_URL, \FILTER_FLAG_SCHEME_REQUIRED | \FILTER_FLAG_HOST_REQUIRED)
            !== false;
    }
}

