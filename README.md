# Simple Input Sanitizer with auto Type-Casting

Sanitizer, Filter, Custom Filter, Hepler, Type-Casting for Inputs

- Apply Filter on your Inputs-data.
- If no Filter was found/defined, automatic Type-Casting will be apply on Inputs-data.
- You can defined Keys, witch will returns from Inputs-data.

## Table of Contents

- [Installation](#installation)
- [Usage](#useage)
- [Inputs](#inputs)
- [Filters](#filters)
- [Keys](#keys)
- [Example](#example)
- [Custom Rule](#custom-rule)

## Installation

Run `composer command`:

```
composer require brunogab/inputhelper
```

or add to your composer.json and run `composer update`:

```json
{
	"require": {
		"brunogab/inputhelper": "^1.0"
	}
}
```

## NOTICE:

> Some value cannot be decided between bool-type and integer/string type ("1", 1, 0, on, off..)<br>
> Bool value check has a higher priority `by automatic type-casting:`<br>
>
> - `1 is bool-true (not integer)`
> - `'1' is bool-true (not string)`
> - `'true' is bool-true (not string)`
> - `'on' is bool-true (not string)`
> - `'yes' is bool-true (not string)`

> - `0 is bool-false (not integer)`
> - `'0' is bool-false (not string)`
> - `'false' is bool-false (not string)`
> - `'off' is bool-false (not string)`
> - `'no' is bool-false (not string)`

> - `null is bool-false (not null)`
> - `'' is bool-false (not empty)`

## Usage

```php
use Brunogab\InputHelper\InputHelper;

$inputhelper = new InputHelper;
$result = $inputhelper->run($inputs, $filters, $keys);
```

## Inputs

Inputs must be an Array:

```php
$inputs = [
	'key_a' => 'Value_A',
	'key_b' => 'value_B',
	'key_c' => 'Value_c',
];
```

## Filters

Filter can be Empty:

```php
$filters = ''; //automatic type-casting will be applied for ALL input value
```

Filter can be String:

```php
$filters = 'trim'; //trim will be applied for ALL input value
```

Filter can be String with Pipe:

```php
$filters = 'trim|upper'; //trim and upper will be applied for ALL input value
```

Filter can be Sequential Array:

```php
$filters = ['trim','upper']; //trim and upper will be applied for ALL input value
```

Filter can be Associative Array:

```php
$filters = [
	'key_a' => 'trim',
	'key_b' => 'trim|upper',
	'key_c' => ['trim'],
	'key_d' => ['trim', 'upper'],
	'key_e' => ['trim', 'upper', function ($val) {
		return $val.' + closure';
	}],
	'key_f' => function ($val) {
		return $val.' + closure';
	}
];
```

> Notice: <br>
> For Inputs where no Filter was found: `automatic type-casting` will be applied

## Keys

Keys can be Empty:

```php
$keys = ''; //Result: (array) Inputs
```

Keys can be String:

```php
$keys = 'key_a'; //key_a value will be returned, Result: (string) "VALUE_A"
```

> Notice: <br>
> If the requested key was not found, Result will be: NULL

Keys can be String with Pipe:

```php
$keys = 'key_a|key_b'; //key_a and key_b value will be returned, Result: array("VALUE_A","VALUE_B")
```

> Notice: <br>
> If none of the requested keys were found, Result will be: NULL

Keys can be Sequential Array:

```php
$keys = [
	'key_a',
	'key_b',
	'key_invalid', //not valid key -> will be ignored
];
//Result: array("VALUE_A","VALUE_B")
```

> Notice: <br>
> If none of the requested keys were found, result will be: NULL

## Example

> see in example.php

Automatic Type-Casting for Boolean Values

```php
/** Automatic Type-Casting for Boolean Values */
$inputs = [
	'col_bool_1' => '1',
	'col_bool_1_1' => 1,
	'col_bool_true' => 'true',
	'col_bool_on' => 'on',
	'col_bool_yes' => 'yes',

	'col_bool_0' => '0',
	'col_bool_0_0' => 0,
	'col_bool_false' => 'false',
	'col_bool_off' => 'off',
	'col_bool_no' => 'no',

	'col_bool_null' => null,
	'col_bool_empty' => '',
];

/* Result:
array (size=12)
	'col_bool_1' => boolean true
	'col_bool_1_1' => boolean true
	'col_bool_true' => boolean true
	'col_bool_on' => boolean true
	'col_bool_yes' => boolean true

	'col_bool_0' => boolean false
	'col_bool_0_0' => boolean false
	'col_bool_false' => boolean false
	'col_bool_off' => boolean false
	'col_bool_no' => boolean false

	'col_bool_null' => boolean false
	'col_bool_empty' => boolean false
*/
```

## Custom Rule

You can write your custom Code and save into src/Filters/`Yourcodename`Filter.php

```php
namespace Brunogab\InputHelper\Filters;

class YourcodenameFilter
{
	public function do($val)
	{
		//custom logic
		return is_bool($val) ? 'Y' : 'N';
	}
}
//then simply use your custom filter
$filters = [
	'col_a' => 'yourcodename'
];
```
