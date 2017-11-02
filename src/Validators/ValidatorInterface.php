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


interface ValidatorInterface {
    /**
     * Validates subject
     *
     * @param $subject
     * @return bool
     */
    public function validate($subject): bool;
}
