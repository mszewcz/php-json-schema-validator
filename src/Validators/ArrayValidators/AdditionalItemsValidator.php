<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator\Validators\ArrayValidators;


use MS\Json\SchemaValidator\Schema\Resolver;
use MS\Json\SchemaValidator\Validators\NodeValidator;
use MS\Json\SchemaValidator\Validators\ValidatorInterface;

class AdditionalItemsValidator implements ValidatorInterface
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
     * AdditionalItemsValidator constructor.
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
     * Validates subject against additionalItems
     *
     * @param $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        if (isset($this->schema['items']) && \is_array($this->schema['items'])) {
            $schemaFirstKey = \array_keys($this->schema['items'])[0];
            $schemaType = \is_int($schemaFirstKey) ? 'array' : 'object';

            if ($schemaType === 'array') {
                if (!$this->validateAdditionalItems($subject, $this->schema)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Validates against additionalItems
     *
     * @param array $subject
     * @param array $schema
     * @return bool
     */
    private function validateAdditionalItems(array $subject, array $schema): bool
    {
        $subject = \array_slice($subject, count($schema['items']));

        if (\is_bool($schema['additionalItems'])) {
            $schemaType = 'boolean';
        } else {
            $schemaFirstKey = \array_keys($schema['additionalItems'])[0];
            $schemaType = \is_int($schemaFirstKey) ? 'array' : 'object';
        }

        if (($schemaType === 'boolean') && !$this->validateAdditionalItemsSchemaBoolean($subject, $schema)) {
            return false;
        }
        if (($schemaType === 'object') && !$this->validateAdditionalItemsSchemaObject($subject, $schema)) {
            return false;
        }
        if (($schemaType === 'array') && !$this->validateAdditionalItemsSchemaArray($subject, $schema)) {
            return false;
        }
        return true;
    }

    /**
     * Validate against additionalItems for boolean-type schema
     *
     * @param array $subject
     * @param array $schema
     * @return bool
     */
    private function validateAdditionalItemsSchemaBoolean(array $subject, array $schema): bool
    {
        if (($schema['additionalItems'] === false) && count($subject) > 0) {
            return false;
        }
        return true;
    }

    /**
     * Validate against additionalItems for object-type schema
     *
     * @param array $subject
     * @param array $schema
     * @return bool
     */
    private function validateAdditionalItemsSchemaObject(array $subject, array $schema): bool
    {
        for ($i = 0; $i < count($subject); $i++) {
            $nodeValidator = new NodeValidator($schema['additionalItems'], $this->rootSchema);
            if (!$nodeValidator->validate($subject[$i])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate against additionalItems for array-type schema
     *
     * @param array $subject
     * @param array $schema
     * @return bool
     */
    private function validateAdditionalItemsSchemaArray(array $subject, array $schema): bool
    {
        for ($i = 0; $i < count($schema['additionalItems']); $i++) {
            if (!isset($subject[$i])) {
                return true;
            }
            $nodeValidator = new NodeValidator($schema['additionalItems'][$i], $this->rootSchema);
            if (!$nodeValidator->validate($subject[$i])) {
                return false;
            }
        }
        return true;
    }
}

