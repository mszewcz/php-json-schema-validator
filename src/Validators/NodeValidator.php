<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator\Validators;


use MS\Json\SchemaValidator\Schema\Resolver;

class NodeValidator implements ValidatorInterface
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
     * @var array
     */
    private $validatorMap = [
        'type'                 => 'MS\Json\SchemaValidator\Validators\MiscValidators\TypeValidator',
        'enum'                 => 'MS\Json\SchemaValidator\Validators\MiscValidators\EnumValidator',
        'const'                => 'MS\Json\SchemaValidator\Validators\MiscValidators\ConstValidator',
        'allOf'                => 'MS\Json\SchemaValidator\Validators\MiscValidators\AllOfValidator',
        'anyOf'                => 'MS\Json\SchemaValidator\Validators\MiscValidators\AnyOfValidator',
        'oneOf'                => 'MS\Json\SchemaValidator\Validators\MiscValidators\OneOfValidator',
        'not'                  => 'MS\Json\SchemaValidator\Validators\MiscValidators\NotValidator',
        'propertyNames'        => 'MS\Json\SchemaValidator\Validators\MiscValidators\PropertyNamesValidator',
        'exclusiveMaximum'     => 'MS\Json\SchemaValidator\Validators\NumericValidators\ExclusiveMaximumValidator',
        'exclusiveMinimum'     => 'MS\Json\SchemaValidator\Validators\NumericValidators\ExclusiveMinimumValidator',
        'maximum'              => 'MS\Json\SchemaValidator\Validators\NumericValidators\MaximumValidator',
        'minimum'              => 'MS\Json\SchemaValidator\Validators\NumericValidators\MinimumValidator',
        'multipleOf'           => 'MS\Json\SchemaValidator\Validators\NumericValidators\MultipleOfValidator',
        'format'               => 'MS\Json\SchemaValidator\Validators\StringValidators\FormatValidator',
        'maxLength'            => 'MS\Json\SchemaValidator\Validators\StringValidators\MaxLengthValidator',
        'minLength'            => 'MS\Json\SchemaValidator\Validators\StringValidators\MinLengthValidator',
        'pattern'              => 'MS\Json\SchemaValidator\Validators\StringValidators\PatternValidator',
        'additionalItems'      => 'MS\Json\SchemaValidator\Validators\ArrayValidators\AdditionalItemsValidator',
        'contains'             => 'MS\Json\SchemaValidator\Validators\ArrayValidators\ContainsValidator',
        'items'                => 'MS\Json\SchemaValidator\Validators\ArrayValidators\ItemsValidator',
        'maxItems'             => 'MS\Json\SchemaValidator\Validators\ArrayValidators\MaxItemsValidator',
        'minItems'             => 'MS\Json\SchemaValidator\Validators\ArrayValidators\MinItemsValidator',
        'uniqueItems'          => 'MS\Json\SchemaValidator\Validators\ArrayValidators\UniqueItemsValidator',
        'additionalProperties' => 'MS\Json\SchemaValidator\Validators\ObjectValidators\AdditionalPropertiesValidator',
        'dependencies'         => 'MS\Json\SchemaValidator\Validators\ObjectValidators\DependenciesValidator',
        'maxProperties'        => 'MS\Json\SchemaValidator\Validators\ObjectValidators\MaxPropertiesValidator',
        'minProperties'        => 'MS\Json\SchemaValidator\Validators\ObjectValidators\MinPropertiesValidator',
        'patternProperties'    => 'MS\Json\SchemaValidator\Validators\ObjectValidators\PatternPropertiesValidator',
        'properties'           => 'MS\Json\SchemaValidator\Validators\ObjectValidators\PropertiesValidator',
        'required'             => 'MS\Json\SchemaValidator\Validators\ObjectValidators\RequiredValidator',
    ];

    /**
     * NodeValidator constructor.
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
     * Single node validator chain
     *
     * @param $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        foreach ($this->validatorMap as $validatorName => $validatorClass) {
            if (\array_key_exists($validatorName, $this->schema)) {
                /**
                 * @var ValidatorInterface $typeValidator
                 */
                $typeValidator = new $validatorClass($this->schema, $this->rootSchema);
                if (!$typeValidator->validate($subject)) {
                    return false;
                }
            }
        }
        return true;
    }
}
