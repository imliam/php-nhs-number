<?php

namespace ImLiam\NhsNumber\Tests\Unit;

use ImLiam\NhsNumber\NhsNumber;
use ImLiam\NhsNumber\Tests\TestCase;
use ImLiam\NhsNumber\InvalidNhsNumberException;

class NhsNumberTest extends TestCase
{
    public function validNhsNumbers()
    {
        return [
            ['9077844449'],
            ['4698651433'],
            ['5835160933'],
            ['5462903022'],
            ['1032640960'],
            ['1740296788'],
            ['9278462608'],
            ['7448556886'],
            ['0372104223'],
            ['8416367035'],
        ];
    }

    /** @test */
    public function a_string_is_not_a_valid_nhs_number()
    {
        $this->expectException(InvalidNhsNumberException::class);
        $this->expectExceptionMessage('An NHS number must be numeric and 10 characters long.');
        (new NhsNumber('Hello world.'))->validate();
    }

    /** @test */
    public function a_number_must_not_have_less_than_10_digits()
    {
        $this->expectException(InvalidNhsNumberException::class);
        $this->expectExceptionMessage('An NHS number must be numeric and 10 characters long.');
        (new NhsNumber(12345))->validate();
    }

    /** @test */
    public function a_number_must_not_have_more_than_10_digits()
    {
        $this->expectException(InvalidNhsNumberException::class);
        $this->expectExceptionMessage('An NHS number must be numeric and 10 characters long.');
        (new NhsNumber(12345678901234567890))->validate();
    }

    /** @test */
    public function a_number_digit_check_must_not_match()
    {
        $this->expectException(InvalidNhsNumberException::class);
        $this->expectExceptionMessage('The NHS number\'s check digit does not match.');
        (new NhsNumber(1234567890))->validate();
    }

    /**
     * @test
     * @dataProvider validNhsNumbers
     */
    public function these_nhs_numbers_are_valid($validNhsNumber)
    {
        $this->assertTrue((new NhsNumber($validNhsNumber))->validate());
    }

    /** @test */
    public function a_random_valid_nhs_number_can_be_generated()
    {
        $number = NhsNumber::getRandomNumber();
        $this->assertTrue((new NhsNumber($number))->validate());
    }

    /** @test */
    public function a_list_of_random_valid_nhs_numbers_can_be_generated()
    {
        $numbers = NhsNumber::getRandomNumbers(3);

        $this->assertCount(3, $numbers);

        foreach ($numbers as $number) {
            $this->assertTrue((new NhsNumber($number))->validate());
        }
    }

    /** @test */
    public function a_valid_nhs_number_can_be_formatted_as_a_string()
    {
        $number = new NhsNumber('9077844449');

        $this->assertEquals('907 784 4449', $number->format());
    }

    /** @test */
    public function a_random_valid_nhs_number_can_be_validated()
    {
        $number = NhsNumber::getRandomNumber();
        $this->assertTrue((new NhsNumber($number))->isValid());
    }

    /** @test */
    public function a_invalid_nhs_number_can_be_validated()
    {
        $this->assertFalse((new NhsNumber('1000'))->isValid());
    }

    /** @test */
    public function it_can_get_number()
    {
        $number = new NhsNumber('9077844449');

        $this->assertEquals('9077844449', $number->getNumber());
    }

    /** @test */
    public function it_can_get_formatted_nhs_number()
    {
        $number = new NhsNumber('9077844449');

        $this->assertEquals('907 784 4449', (string) $number);
    }
}
