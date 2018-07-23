<?php

namespace ImLiam\NhsNumber;

class NhsNumber
{
    /**
     * The original NHS number.
     *
     * @var string
     */
    protected $number;

    /**
     * The multipliers used when validating the NHS number.
     *
     * @var array
     */
    protected $multipliers = [
        1 => 10,
        2 => 9,
        3 => 8,
        4 => 7,
        5 => 6,
        6 => 5,
        7 => 4,
        8 => 3,
        9 => 2,
    ];

    /**
     * Construct the NHS number object.
     *
     * @param string|int $number
     */
    public function __construct($number)
    {
        $this->number = (string) trim($number);
    }

    /**
     * Determine whether or not the current NHS number is valid.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        try {
            $this->validate();
        } catch (InvalidNhsNumberException $e) {
            return false;
        }

        return true;
    }

    /**
     * Validate the current NHS number.
     *
     * @return bool
     * @throws InvalidNhsNumberException
     */
    public function validate(): bool
    {
        if (! preg_match('/^[0-9]{10}$/', $this->number)) {
            throw new InvalidNhsNumberException('An NHS number must be numeric and 10 characters long.');
        }

        $total = $this->getChecksumTotal();
        $checkNumber = (int) substr($this->number, 9, 1);

        if ($total !== $checkNumber) {
            throw new InvalidNhsNumberException('The NHS number\'s check digit does not match.');
        }

        return true;
    }

    /**
     * Loop over each digit of the current NHS number and calculate
     * the appropriate checksum based on the predefined multipliers.
     *
     * @return int
     */
    protected function getChecksum(): int
    {
        $currentSum = 0;
        $currentMultiplier = 0;
        $currentNumber = 0;

        for ($i = 0; $i <= 8; $i++) {
            $currentNumber = substr($this->number, $i, 1);
            $currentMultiplier = $this->multipliers[$i + 1];
            $currentSum = $currentSum + ($currentNumber * $currentMultiplier);
        }

        return $currentSum;
    }

    /**
     * Calculate.
     *
     * @return int
     */
    protected function getChecksumTotal(): int
    {
        $remainder = $this->getChecksum() % 11;
        $total = 11 - $remainder;

        if ($total === 11) {
            $total = 0;
        }

        return $total;
    }

    /**
     * Get the raw NHS number.
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Format the NHS number as a string by spacing it out in a 3-3-4 fashion.
     *
     * @return string
     */
    public function format(): string
    {
        $formattedNumber = (string) $this->number;
        $formattedNumber = substr_replace($formattedNumber, ' ', 3, 0);
        $formattedNumber = substr_replace($formattedNumber, ' ', 7, 0);

        return $formattedNumber;
    }

    /**
     * Get a human readable string of the NHS number.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }

    /**
     * Generate a single random NHS number.
     *
     * @param bool $unique
     * @return string
     */
    public static function getRandomNumber(): string
    {
        return static::getRandomNumbers(1)[0];
    }

    /**
     * Generate a list of random NHS numbers.
     *
     * @param int $count
     * @param bool $unique
     * @return array
     */
    public static function getRandomNumbers(int $count = 1, bool $unique = true): array
    {
        $numbers = [];

        while (count($numbers) < $count) {
            $number = mt_rand(1000000000, 9999999999);

            try {
                (new static($number))->validate();
            } catch (InvalidNhsNumberException $e) {
                continue;
            }

            if ($unique && in_array($number, $numbers)) {
                continue;
            }

            $numbers[] = $number;
        }

        return $numbers;
    }
}
