<?php
/**
 * Example File
 */

require_once './vendor/autoload.php';

use Brunogab\InputHelper\InputHelper as ih;

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
//$filters = 'trim';

/** Filters can be empty -> automatic casting type*/

/** Keys to return */
// $keys = [
// 	'col_a',
// 	'col_b',
// 	'asd', //not valid keys -> will be ignored
// ];

/** Keys can be string*/
//$keys = 'col_bool_d';

$keys = null;


$start__ = microtime(true);

$result = (new ih())->run($inputs, $filters, $keys);

$end__ = microtime(true);

var_dump($inputs);
var_dump($result);

echo 'ToTime: ' . ($end__ - $start__) . '<br>';
$mem = memory_get_usage(false);
echo 'Memory: ' . round($mem / 1024, 4) . ' kilobytes <br	>';
echo 'Memory: ' . round($mem / 1048576, 4) . ' megabytes<br	>';
