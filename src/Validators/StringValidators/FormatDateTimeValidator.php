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

class FormatDateTimeValidator implements ValidatorInterface
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
        $pattern = '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(\.\d{2})?(Z|([+-](\d{2}):(\d{2})))$/';
        if (!\preg_match($pattern, $subject, $matches)) {
            return false;
        }

        $dateYear = \intval($matches[1]);
        $dateMonth = \intval($matches[2]);
        $dateDay = \intval($matches[3]);
        $timeHour = \intval($matches[4]);
        $timeMinutes = \intval($matches[5]);
        $timeSeconds = \intval($matches[6]);
        $tzTime = null;
        $tzTimeHours = 0;
        $tzTimeMinutes = 0;

        if (isset($matches[9])) {
            $tzTime = $matches[9];
        }
        if (isset($matches[10])) {
            $tzTimeHours = \intval($matches[10]);
        }
        if (isset($matches[11])) {
            $tzTimeMinutes =  \intval($matches[11]);
        }

        if (!$this->validateDate($dateYear, $dateMonth, $dateDay)) {
            return false;
        }
        if (!$this->validateTime($timeHour, $timeMinutes, $timeSeconds)) {
            return false;
        }
        if ($tzTime !== null && !$this->validateTimezoneTime($tzTimeHours, $tzTimeMinutes)) {
            return false;
        }
        return true;
    }

    /**
     * Validates date
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return bool
     */
    private function validateDate(int $year, int $month, int $day): bool
    {
        if (!$this->validateMonth($month)) {
            return false;
        }
        if (!$this->validateDay($day)) {
            return false;
        }
        if (!\checkdate($month, $day, $year)) {
            return false;
        }
        return true;
    }

    /**
     * Validates time
     *
     * @param int $hour
     * @param int $minutes
     * @param int $seconds
     * @return bool
     */
    private function validateTime(int $hour, int $minutes, int $seconds): bool
    {
        if (!$this->validateHour($hour)) {
            return false;
        }
        if (!$this->validateMinutes($minutes)) {
            return false;
        }
        if (!$this->validateSeconds($seconds)) {
            return false;
        }
        return true;
    }

    /**
     * Validates timezone time
     *
     * @param int $hour
     * @param int $minutes
     * @return bool
     */
    private function validateTimezoneTime(int $hour, int $minutes): bool
    {
        if (!$this->validateHour($hour)) {
            return false;
        }
        if (!$this->validateMinutes($minutes)) {
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
}

