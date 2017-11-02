<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator\Exceptions;


use Throwable;

class LoadSchemaException extends \Exception
{
    /**
     * LoadSchemaException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        $message = \sprintf('Cannot load schema from: %s', $message);
        parent::__construct($message, $code, $previous);
    }
}
