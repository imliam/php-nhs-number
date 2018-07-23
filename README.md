# NHS Numbers

[![Latest Version on Packagist](https://img.shields.io/packagist/v/imliam/php-nhs-number.svg)](https://packagist.org/packages/imliam/php-nhs-number)
[![Build Status](https://img.shields.io/travis/imliam/php-nhs-number.svg)](https://travis-ci.org/imliam/php-nhs-number)
![Code Quality](https://img.shields.io/scrutinizer/g/imliam/php-nhs-number.svg)
![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/imliam/php-nhs-number.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/imliam/php-nhs-number.svg)](https://packagist.org/packages/imliam/php-nhs-number)
[![License](https://img.shields.io/github/license/imliam/php-nhs-number.svg)](LICENSE.md)

Utility class to validate, format and generate NHS numbers.

<!-- TOC -->

- [NHS Numbers](#nhs-numbers)
    - [F.A.Q.](#faq)
        - [What is an NHS number?](#what-is-an-nhs-number)
        - [How do you validate an NHS number?](#how-do-you-validate-an-nhs-number)
    - [💾 Installation](#💾-installation)
    - [📝 Usage](#📝-usage)
    - [✅ Testing](#✅-testing)
    - [🔖 Changelog](#🔖-changelog)
    - [⬆️ Upgrading](#⬆️-upgrading)
    - [🎉 Contributing](#🎉-contributing)
        - [🔒 Security](#🔒-security)
    - [👷 Credits](#👷-credits)
    - [♻️ License](#♻️-license)

<!-- /TOC -->

## F.A.Q.

### What is an NHS number?

An "NHS number" is a unique identifier that every individual patient registered with Great Britain's National Health Service (NHS) has.

[You can find out more about NHS numbers on the nhs.uk website.](https://www.nhs.uk/NHSEngland/thenhs/records/nhs-number/Pages/what-is-the-nhs-number.aspx)

### How do you validate an NHS number?

Not every number is a valid NHS number - they must conform to a simple algorithm to be considered valid. Before the algorithm is run, there are a few things to note about NHS numbers:

- An NHS number must be numeric
- An NHS number must be 10 digits long
- An NHS number can begin with a `0`, so it should be handled as a string, not an integer
- The last digit of the NHS number is used as the "check digit" for the algorithm

The algorithm to validate an NHS number using its "check digit" is as follows:

1. Multiple each of the first nine digits by a defined weight, shown below:

| Original digit | Multiplied by |
| -------------- | ------------- |
| 1              | 10            |
| 2              | 9             |
| 3              | 8             |
| 4              | 7             |
| 5              | 6             |
| 6              | 5             |
| 7              | 4             |
| 8              | 3             |
| 9              | 2             |

2. Calculate the sum of all 9 multiplications
3. Divide this sum by 11 and get the remainder
4. Subtract 11 from the remainder to get the total
5. If the total is 11 then the identifier, otherwise the identifier is the total
6. If the identifier is 10, then the NHS number is wrong
7. If the identifier is the same as the check digit, then the NHS number is correct

## 💾 Installation

You can install the package with [Composer](https://getcomposer.org/) using the following command:

```bash
composer require imliam/php-nhs-number:^1.0.0
```

## 📝 Usage

You can new up the `NhsNumber` object by passing through an NHS number.

``` php
$nhsNumber = new \ImLiam\NhsNumber\NhsNumber('9077844449');
```

The NHS recommend that when displaying an NHS number to a human, it should be spaced out in a 3-3-4 format, which helps it to be easier to read. To do this, call the `->format()` method or cast the object to a string.

```php
echo $nhsNumber->format();
// '907 784 4449'

echo (string) $nhsNumber;
// '907 784 4449'
```

To get a boolean representation of whether or not the current number is valid, call the `->isValid()` method on the object.

```php
$nhsNumber->isValid();
// true
```

If you need more information on why a given NHS number may be invalid, you can call the `->validate()` method directly. This will throw an `InvalidNhsNumberException` exception whose message will explain why the number is not valid.

```php
try {
    $nhsNumber = new \ImLiam\NhsNumber\NhsNumber('9077844446');
    $nhsNumber->validate();
} catch (\ImLiam\NhsNumber\InvalidNhsNumberException $e) {
    echo $e->getMessage();
    // "The NHS number's check digit does not match."
}
```

To generate a single or multiple random valid NHS numbers for testing purposes, call the `::generateRandomNumber()` or `::generateRandomNumber($count)` static methods respectively.

```php
echo \ImLiam\NhsNumber\NhsNumber::generateRandomNumber();
// '9278462608'

echo \ImLiam\NhsNumber\NhsNumber::generateRandomNumbers(5);
// ['7448556886', '0372104223', '8416367035']
```

## ✅ Testing

``` bash
composer test
```

## 🔖 Changelog

Please see [the changelog file](CHANGELOG.md) for more information on what has changed recently.

## ⬆️ Upgrading

Please see the [upgrading file](UPGRADING.md) for details on upgrading from previous versions.

## 🎉 Contributing

Please see the [contributing file](CONTRIBUTING.md) and [code of conduct](CODE_OF_CONDUCT.md) for details on contributing to the project.

### 🔒 Security

If you discover any security related issues, please email liam@liamhammett.com instead of using the issue tracker.

## 👷 Credits

- [Liam Hammett](https://github.com/imliam)
- [Peter Fisher](https://github.com/pfwd/NHSNumber-Validation) for the original class
- [All Contributors](../../contributors)

## ♻️ License

The MIT License (MIT). Please see the [license file](LICENSE.md) for more information.
