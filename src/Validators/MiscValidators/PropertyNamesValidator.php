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
use MS\Json\SchemaValidator\Validators\NodeValidator;
use MS\Json\SchemaValidator\Validators\ValidatorInterface;

class PropertyNamesValidator implements ValidatorInterface
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
     * PropertyNamesValidator constructor.
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
     * Validates subject against propertyNames
     *
     * @param $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        $propertyNames = \array_keys($subject);
        foreach ($propertyNames as $propertyName) {
            $nodeValidator = new NodeValidator($this->schema['propertyNames'], $this->rootSchema);
            if (!$nodeValidator->validate($propertyName)) {
                return false;
            }
        }
        return true;
    }
}
