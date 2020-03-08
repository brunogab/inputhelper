# Simple Input Sanitizer with auto Type-Casting

The most sanitizer loop your filters and apply it on input-data. `This helper:`

- loop your inputs-data and search for filter and apply it.
- If no filter was found or not defined, auto Type-Casting will be apply on inputs-data.
- You can defined keys, witch will returns from inputs-data.

## Installation

Run `composer command`:

```
composer require brunogab/inputhelper: "dev-master"
```

or add to your composer.json and run `composer update`:

```json
{
	"require": {
		"brunogab/inputhelper": "dev-master"
	}
}
```

## usage and basic example in `example.php`

> NOTICE:<br>
> Some value cannot be decided between bool-type and interer/string type ("1", 1, 0, on, off..)<br>
> Bool value check has a higher priority `by auto type-casting`:<br>
>
> - `1 is bool-true (not integer)`
> - `0 is bool-false (not integer)`
> - `on is bool-true (not string)`
> - `off is bool-false (not integer)`
> - `null is bool-false (not empty)`
> - `empty is bool-false (not empty)`

- Inputs must be Array
- Filters can be String or Array or Closure or empty
- Keys can be be String or Array or empty

```php
use Brunogab\InputHelper\InputHelper;

/** Input Data */
$inputs = [
	'col_a' => ' ColA ',
	'col_b' => ' COlB',
	'col_c' => ' colc* ',
	'col_d' => ' colD ',
	'col_int' => ' 22 ', //given as string with space
	'col_float' => ' 184.5 ', //given as string with space

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
```

- all these Filters Styles are valid:

```php
/** Filters */
$filters = [
	'col_a' => ['trim', 'lower'], //array style
	'col_b' => 'trim|upper', //sting with a pipe style
	'col_c' => ['trim', 'lower', function ($value) { //closure in array style
		return str_replace('*', '+ replaced by closure', $value);
	}],
	'col_d' => function ($value) { //closure
		$value = trim($value);
		return ucfirst($value);
	},
	'col_int' => 'int',
	'col_float' => 'dummy' //dummy is not valid filter -> auto type-casting
	//Notice: bool has no filter defined -> auto type-casting
];

/** Filter can be String -> apply for all inputs-data */
$filters = 'trim';

/** Keys to return */
$keys = [
	'col_a',
	'col_b',
	'asd', //not valid keys -> will be ignored
];

/** Keys can be string*/
$keys = 'col_b';

$inputhelper = new InputHelper;
$result = $inputhelper->run($inputs, $filters, $keys);

//Result without Keys:
array (size=18)
  'col_a' => string 'cola' (length=4)
  'col_b' => string 'COLB' (length=4)
  'col_c' => string 'colc+ replaced by closure' (length=25)
  'col_d' => string 'ColD' (length=4)
  'col_int' => int 22
  'col_float' => float 184.5
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
```

## Custom Sanitizers

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
//then
$filters = [
	//simply use your custom filter
	'col_a' => 'yourcodename',
];
```
