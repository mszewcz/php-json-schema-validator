<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator\Validators\ObjectValidators;


use MS\Json\SchemaValidator\Schema\Resolver;
use MS\Json\SchemaValidator\Validators\NodeValidator;
use MS\Json\SchemaValidator\Validators\ValidatorInterface;

class PatternPropertiesValidator implements ValidatorInterface
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
     * PatternPropertiesValidator constructor.
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
     * Validates subject against patternProperties
     *
     * @param array $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        if (\is_array($this->schema['patternProperties'])) {
            foreach ($this->schema['patternProperties'] as $pattern => $patternSchema) {
                foreach ($subject as $propertyName => $propertyData) {
                    if (\preg_match(sprintf('/%s/', $pattern), $propertyName)) {
                        $nodeValidator = new NodeValidator($patternSchema, $this->rootSchema);
                        if (!$nodeValidator->validate($propertyData)) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }
}
