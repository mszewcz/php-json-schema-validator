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
                return $this->validateFormatDateTime($subject);
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
     * 'date-time' format validation
     *
     * @param string $subject
     * @return bool
     */
    private function validateFormatDateTime(string $subject): bool
    {
        $pattern = '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(\.\d{2})?(Z|([+-](\d{2}):(\d{2})))$/';
        if (!\preg_match($pattern, $subject, $matches)) {
            return false;
        }

        $dateYear = \intval($matches[1]);
        $dateMonth = \intval($matches[2]);
        $dateDay = \intval($matches[3]);
        $timeHour = \intval($matches[4]);
        $timeMinute = \intval($matches[5]);
        $timeSeconds = \intval($matches[6]);
        $tzTime = isset($matches[9]) ? $matches[9] : null;
        $tzTimeHours = isset($matches[10]) ? \intval($matches[10]) : null;
        $tzTimeMinutes = isset($matches[11]) ? \intval($matches[11]) : null;

        if (!$this->validateMonth($dateMonth)) {
            return false;
        }
        if (!$this->validateDay($dateDay)) {
            return false;
        }
        if (!$this->validateHour($timeHour)) {
            return false;
        }
        if (!$this->validateMinutes($timeMinute)) {
            return false;
        }
        if (!$this->validateSeconds($timeSeconds)) {
            return false;
        }
        if ($tzTime !== null) {
            if (!$this->validateHour($tzTimeHours)) {
                return false;
            }
            if (!$this->validateMinutes($tzTimeMinutes)) {
                return false;
            }
        }
        if (!\checkdate($dateMonth, $dateDay, $dateYear)) {
            return false;
        }
        return true;
    }

    /**
     * Validates month
     *
     * @param int $month
     * @return bool
     */
    private function validateMonth(int $month): bool
    {
        if ($month < 1 || $month > 12) {
            return false;
        }
        return true;
    }

    /**
     * Validates day
     *
     * @param int $day
     * @return bool
     */
    private function validateDay(int $day): bool
    {
        if ($day < 1 || $day > 31) {
            return false;
        }
        return true;
    }

    /**
     * Validates hour
     *
     * @param int $hour
     * @return bool
     */
    private function validateHour(int $hour): bool
    {
        if ($hour < 0 || $hour > 23) {
            return false;
        }
        return true;
    }

    /**
     * Validates minutes
     *
     * @param int $minutes
     * @return bool
     */
    private function validateMinutes(int $minutes): bool
    {
        if ($minutes < 0 || $minutes > 59) {
            return false;
        }
        return true;
    }

    /**
     * Validates seconds
     *
     * @param int $seconds
     * @return bool
     */
    private function validateSeconds(int $seconds):bool
    {
        if ($seconds < 0 || $seconds > 60) {
            return false;
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

