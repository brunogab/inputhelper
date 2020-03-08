<?php

use Brunogab\InputHelper\InputHelper as ih;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
	public function test_inputs__filter_arrayvalue_is_string()
	{
		$inputs = [
			'col_a' => ' FiRsT ',
			'col_b' => 'TesT',
		];
		$filters = [
			'col_a' => 'trim', //use string style
			'col_b' => 'upper', //use string style
		];
		$result = [
			'col_a' => 'FiRsT',
			'col_b' => 'TEST'
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));
	}

	public function test_inputs__filter_arrayvalue_is_string_with_pipe()
	{
		$inputs = [
			'col_a' => ' SeCond ',
		];
		$filters = [
			'col_a' => 'trim|lower', //use string with pipe style
		];
		$result = [
			'col_a' => 'second',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));
	}

	public function test_inputs__filter_arrayvalue_is_array_with_string()
	{
		$inputs = [
			'col_a' => ' tHiRd ',
		];
		$filters = [
			'col_a' => ['trim', 'upper'], //use array style
		];
		$result = [
			'col_a' => 'THIRD',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));
	}

	public function test_inputs__filter_arrayvalue_is_array_with_closure()
	{
		$inputs = [
			'col_a' => ' FouRth* ',
		];
		$filters = [
			'col_a' => ['trim', 'lower', function ($value) {
				return str_replace('*', '+ replaced by closure', $value);
			}]
		];
		$result = [
			'col_a' => 'fourth+ replaced by closure',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));
	}

	public function test_inputs__filter_arrayvalue_is_closure()
	{
		$inputs = [
			'col_a' => 'fifth*',
		];
		$filters = [
			'col_a' => function ($value) {
				return str_replace('*', '+ replaced by closure', $value);
			}
		];
		$result = [
			'col_a' => 'fifth+ replaced by closure',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));
	}

	public function test_inputs__filter_arrayvalue_has_invalid_filter()
	{
		$inputs = [
			'col_a' => ' Sixth ',
			'col_b' => '22',
		];
		$filters = [
			'col_a' => 'trim',
			'col_b' => 'dummy', // invalid filter ==> if filter for input not found -> calculate type
		];
		$result = [
			'col_a' => 'Sixth',
			'col_b' => 22 //this shuld be an integer (after automatic calculation)
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));
	}

	public function test_inputs__filter_is_empty()
	{
		$inputs = [
			'col_a' => '1',
			'col_b' => '26.6',
			'col_c' => 'true',
			'col_d' => 'on',
		];
		//if filter for input not found -> calculate type
		$result = [
			'col_a' => 1,
			'col_b' => 26.6,
			'col_c' => true,
			'col_d' => true,
		];
		$this->assertEquals($result, (new ih())->run($inputs));
	}

	public function test_inputs__filter_is_string()
	{
		$inputs = [
			'col_a' => ' a ',
			'col_b' => 'b ',
			'col_c' => ' c',
		];
		//if filter is string apply filter for all input inputs
		$filters = 'trim';
		$result = [
			'col_a' => 'a',
			'col_b' => 'b',
			'col_c' => 'c',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));
	}

	public function test_inputs__keys_with_array()
	{
		$inputs = [
			'col_a' => 'First',
			'col_b' => '22',
			'col_c' => '26',
		];
		$filters = [
			'col_c' => 'string',
		];
		$keys = [
			'col_b',
			'col_c',
		];
		$result = [
			'col_b' => 22,
			'col_c' => '26'
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters, $keys));
	}

	public function test_inputs__keys_with_string()
	{
		$inputs = [
			'col_a' => 'First',
			'col_b' => '22',
			'col_c' => '26',
		];
		$filters = [
			'col_b' => 'string',
		];
		$keys = 'col_b';
		$result = [
			'col_b' => '22',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters, $keys));
	}
}
