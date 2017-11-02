<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator\Validators\MiscValidators;


use MS\Json\SchemaValidator\Schema\Resolver;
use MS\Json\SchemaValidator\Validators\ValidatorInterface;

class TypeValidator implements ValidatorInterface
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
     * TypeValidator constructor.
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
     * Validates subject against type
     *
     * @param $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        switch ($this->schema['type']) {
            case 'boolean':
                return $this->validateTypBoolean($subject);
            case 'number':
            case 'integer':
            case 'float':
                return $this->validateTypeNumeric($subject);
            case 'string':
                return $this->validateTypeString($subject);
            case 'array':
                return $this->validateTypeArray($subject);
            case 'object':
                return $this->validateTypeObject($subject);
            case 'null':
                return $this->validateTypeNull($subject);
        }
        return false;
    }

    /**
     * Validates subject against type = boolean
     *
     * @param $subject
     * @return bool
     */
    private function validateTypBoolean($subject): bool
    {
        return \is_bool($subject);
    }

    /**
     * Validates subject against type = numeric/integer/float
     *
     * @param $subject
     * @return bool
     */
    private function validateTypeNumeric($subject): bool
    {
        // number
        if (($this->schema['type'] === 'number') && !\is_integer($subject) && !\is_float($subject)) {
            return false;
        }
        // integer
        if (($this->schema['type'] === 'integer') && !\is_integer($subject)) {
            return false;
        }
        // float
        if (($this->schema['type'] === 'float') && !\is_float($subject)) {
            return false;
        }
        return true;
    }

    /**
     * Validates subject against type = string
     *
     * @param $subject
     * @return bool
     */
    private function validateTypeString($subject): bool
    {
        if (!\is_string($subject)) {
            return false;
        }
        return true;
    }

    /**
     * Validates subject against type = array
     *
     * @param $subject
     * @return bool
     */
    private function validateTypeArray($subject): bool
    {
        if (!\is_array($subject)) {
            return false;
        }
        if (count($subject) > 0 && !\is_int(\array_keys($subject)[0])) {
            return false;
        }
        return true;
    }

    /**
     * Validates subject against type = object
     *
     * @param $subject
     * @return bool
     */
    private function validateTypeObject($subject): bool
    {
        if (!\is_array($subject)) {
            return false;
        }
        if (count($subject) > 0 && !\is_string(\array_keys($subject)[0])) {
            return false;
        }
        return true;
    }

    /**
     * Validates subject against type = null
     *
     * @param $subject
     * @return bool
     */
    private function validateTypeNull($subject): bool
    {
        return \is_null($subject);
    }

}
