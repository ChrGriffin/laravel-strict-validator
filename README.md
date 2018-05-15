'Strict' validator for PHP arrays. Currently only supported in Lumen/Laravel as it uses the Laravel validation implementation. Use this package to add 'strict' validation in addition to the normal Laravel validation rules. 

## 'Strict' Rules

1. Arrays can only contain fields under validation. Any 'extra' indexes will cause the validator to return `false.
`
2. _to be continued..?_

## Installation

```$xslt
composer require chrgriffin/laravel-strict-validator
```

## Usage

```
use ChrGriffin\StrictValidator;
 
$validator = new StrictValidator(
    $dataToValidate,
    [
        'foo'       => 'string|required',
        'bar'       => 'array|required',
        'bar.inner' => 'string|required'
    ]
);

$valid = $validator->validate();
```