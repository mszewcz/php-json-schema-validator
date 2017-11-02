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

class ItemsValidator implements ValidatorInterface
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
     * ItemsValidator constructor.
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
     * Validates subject against items
     *
     * @param $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        if (\is_array($this->schema['items'])) {
            $schemaFirstKey = \array_keys($this->schema['items'])[0];
            $schemaType = \is_int($schemaFirstKey) ? 'array' : 'object';

            if (($schemaType === 'object') && !$this->validateItemsSchemaObject($subject, $this->schema)) {
                return false;
            }
            if ($schemaType === 'array' && !$this->validateItemsSchemaArray($subject, $this->schema)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate against items for object-type schema
     *
     * @param array $subject
     * @param array $schema
     * @return bool
     */
    private function validateItemsSchemaObject(array $subject, array $schema): bool
    {
        for ($i = 0; $i < count($subject); $i++) {
            $nodeValidator = new NodeValidator($schema['items'], $this->rootSchema);
            if (!$nodeValidator->validate($subject[$i])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate against items for array-type schema
     *
     * @param array $subject
     * @param array $schema
     * @return bool
     */
    private function validateItemsSchemaArray(array $subject, array $schema): bool
    {
        for ($i = 0; $i < count($schema['items']); $i++) {
            if (!isset($subject[$i])) {
                return true;
            }
            $nodeValidator = new NodeValidator($schema['items'][$i], $this->rootSchema);
            if (!$nodeValidator->validate($subject[$i])) {
                return false;
            }
        }
        return true;
    }
}

