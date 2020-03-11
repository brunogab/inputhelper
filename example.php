<?php
/**
 * Example File
 */

require_once './vendor/autoload.php';

use Brunogab\InputHelper\InputHelper as ih;

function vardump($inputs, $filters = [], $keys = [], $result = [])
{
	echo '-------------------------------- <br> ';
	echo 'Inputs: ';
	var_dump($inputs);
	echo 'Filters: ';
	var_dump($filters);
	echo 'Keys: ';
	var_dump($keys);
	echo 'Result: ';
	var_dump($result);
	echo '-------------------------------- <br> ';
}

/** Input Data */
$inputs = [
	'key_a' => ' Value a ',
	'key_b' => ' Value b ',
	'key_c' => ' Value c ',
	'key_d' => 'value d',

	'key_int' => ' 22 ',				//given as string with space
	'key_float' => ' 184.5 ',		//given as string with space

	'key_bool_1' => '1',
	'key_bool_1_1' => 1,
	'key_bool_true' => 'true',
	'key_bool_on' => 'on',
	'key_bool_yes' => 'yes',

	'key_bool_0' => '0',
	'key_bool_0_0' => 0,
	'key_bool_false' => 'false',
	'key_bool_off' => 'off',
	'key_bool_no' => 'no',
	'key_bool_null' => null,
	'key_bool_empty' => '',
];

//Filter can be Empty: automatic type-casting will be applied for ALL input value
$filters = '';
$keys = '';
$result = (new ih())->run($inputs);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;

//Filter can be String: trim will be applied for ALL input value
$filters = 'trim';
$result = (new ih())->run($inputs, $filters);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;

//Filter can be String with Pipe: trim and upper will be applied for ALL input value
$filters = 'trim|upper';
$result = (new ih())->run($inputs, $filters);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;

//Filter can be Sequential Array: upper will be applied for ALL input value
$filters = ['upper'];
$result = (new ih())->run($inputs, $filters);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;

//Filter can be Sequential Array: trim and upper will be applied for ALL input value
$filters = ['trim', 'lower'];
$result = (new ih())->run($inputs, $filters);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;

//Filter can be Associative Array:
$filters = [
	'key_a' => ['trim', 'upper'],
	'key_b' => 'trim|upper',
	'key_c' => ['trim', 'upper', function ($val) {
		return ucfirst($val);
	}],
	'key_d' => function ($val) {
		return ucfirst($val . ' Closure');
	}
];
$result = (new ih())->run($inputs, $filters);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;


/** 
 * Input Data 
 */
$inputs = [
	'key_a' => 'Value_a',
	'key_b' => 'Value_b',
	'key_c' => 'Value_c'
];

//Keys can be Empty: Result: (array) Inputs
$filters = 'upper';
$keys = '';
$result = (new ih())->run($inputs, $filters, $keys);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;

//Keys can be String: 
$filters = 'upper';
$keys = 'key_b';
$result = (new ih())->run($inputs, $filters, $keys);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;

//Keys can be String with Pipe:
$filters = 'upper';
$keys = 'key_a|key_b';
$result = (new ih())->run($inputs, $filters, $keys);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;

//Keys can be Sequential Array:
$filters = 'upper';
$keys = [
	'key_a',
	'key_b',
	'key_invalid', //not valid keys -> will be ignored
];
$result = (new ih())->run($inputs, $filters, $keys);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;

//All Keys are Invalid: RETURN NULL
$filters = 'upper';
$keys = [
	'key_invalid_a', //not valid keys -> will be ignored
	'key_invalid_b', //not valid keys -> will be ignored
	'key_invalid_c', //not valid keys -> will be ignored
];
$result = (new ih())->run($inputs, $filters, $keys);
vardump($inputs, $filters, $keys, $result); $filters = $keys = $result = null;
