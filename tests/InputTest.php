<?php

use Brunogab\InputHelper\InputHelper as ih;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
	public function test_inputs__filter_is_empty() //ok
	{
		$inputs = [
			'key_a' => '1',
			'key_b' => '26.6',
			'key_c' => 'true',
			'key_d' => 'on',
			'key_e' => '22',
		];
		//if filter for input not found -> calculate type
		$result = [
			'key_a' => true,
			'key_b' => 26.6,
			'key_c' => true,
			'key_d' => true,
			'key_e' => 22,
		];
		$this->assertEquals($result, (new ih())->run($inputs));

		$res = (new ih())->run($inputs);
		$this->assertIsBool($res['key_a']);
		$this->assertIsFloat($res['key_b']);
		$this->assertIsBool($res['key_c']);
		$this->assertIsBool($res['key_d']);
		$this->assertIsInt($res['key_e']);
	}

	public function test_inputs__filter_is_string() //ok
	{
		$inputs = [
			'key_a' => ' Value_A',
			'key_b' => 'Value_b ',
		];
		$filters = 'trim';
		$result = [
			'key_a' => 'Value_A',
			'key_b' => 'Value_b'
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));

		$res = (new ih())->run($inputs, $filters);
		$this->assertIsString($res['key_a']);
		$this->assertIsString($res['key_b']);
	}

	public function test_inputs__filter_is_string_with_pipe() //ok
	{
		$inputs = [
			'key_a' => ' Value_A ',
			'key_b' => ' Value_b ',
		];
		$filters = 'trim|lower';
		$result = [
			'key_a' => 'value_a',
			'key_b' => 'value_b',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));

		$res = (new ih())->run($inputs, $filters);
		$this->assertIsString($res['key_a']);
		$this->assertIsString($res['key_b']);
	}

	public function test_inputs__filter_is_sequential_array() //ok
	{
		$inputs = [
			'key_a' => ' Value_A ',
			'key_b' => ' Value_b ',
		];
		$filters = ['trim', 'upper'];
		$result = [
			'key_a' => 'VALUE_A',
			'key_b' => 'VALUE_B',
		];

		$this->assertEquals($result, (new ih())->run($inputs, $filters));

		$res = (new ih())->run($inputs, $filters);
		$this->assertIsString($res['key_a']);
		$this->assertIsString($res['key_b']);
	}

	public function test_inputs__filter_is_not_assoc_array_with_invalid_filter() //ok
	{
		$inputs = [
			'key_a' => ' Value_A ',
			'key_b' => '22',
		];
		$filters = 'invalid_filter';
		$result = [
			'key_a' => ' Value_A ',
			'key_b' => 22,
		];

		$this->assertEquals($result, (new ih())->run($inputs, $filters));

		$res = (new ih())->run($inputs, $filters);
		$this->assertIsString($res['key_a']);
		$this->assertIsInt($res['key_b']);
		$this->assertIsNotString($res['key_b']);
	}

	public function test_inputs__filter_assoc_array()
	{
		$inputs = [
			'key_a' => ' Value_A ',
			'key_b' => ' Value_b ',
			'key_c' => ' vAlue_C ',
			'key_d' => ' vAlue_d ',
			'key_e' => ' value_E ',
			'key_f' => ' value_F ',
		];
		$filters = [
			'key_a' => 'trim',
			'key_b' => 'trim|upper',
			'key_c' => ['trim'],
			'key_d' => ['trim', 'upper'],
			'key_e' => ['trim', 'upper', function ($val) {
				return $val . ' + closure';
			}],
			'key_f' => function ($val) {
				return $val . ' + closure';
			}
		];
		$result = [
			'key_a' => 'Value_A',
			'key_b' => 'VALUE_B',
			'key_c' => 'vAlue_C',
			'key_d' => 'VALUE_D',
			'key_e' => 'VALUE_E + closure',
			'key_f' => ' value_F  + closure',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));

		$res = (new ih())->run($inputs, $filters);
		$this->assertIsString($res['key_a']);
		$this->assertIsString($res['key_b']);
		$this->assertIsString($res['key_c']);
		$this->assertIsString($res['key_d']);
		$this->assertIsString($res['key_e']);
		$this->assertIsString($res['key_f']);
	}

	public function test_inputs__filter_assoc_array_filter_invalid_or_empty()
	{
		$inputs = [
			'key_a' => ' Value_A ',
			'key_b' => ' Value_b ',
			'key_c' => ' vAlue_C ',
			'key_d' => 'yes',
		];
		$filters = [
			'key_a' => 'trim',
			'key_b' => 'invalid_filter',
			'key_c' => 'trim|upper',
		];
		$result = [
			'key_a' => 'Value_A',
			'key_b' => ' Value_b ',
			'key_c' => 'VALUE_C',
			'key_d' => true,
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters));

		$res = (new ih())->run($inputs, $filters);
		$this->assertIsString($res['key_a']);
		$this->assertIsString($res['key_b']);
		$this->assertIsString($res['key_c']);
		$this->assertIsBool($res['key_d']);
	}

	/**
	 * Keys check
	 */
	public function test_inputs__keys_is_empty()
	{
		$inputs = [
			'key_a' => ' Value_A',
			'key_b' => '22',
			'key_c' => '26',
		];
		$filters = [
			'key_c' => 'string',
		];
		$keys = '';
		$result = [
			'key_a' => ' Value_A',
			'key_b' => 22,
			'key_c' => '26',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters, $keys));

		$res = (new ih())->run($inputs, $filters);
		$this->assertIsString($res['key_a']);
		$this->assertIsInt($res['key_b']);
		$this->assertIsString($res['key_c']);
	}

	public function test_inputs__keys_is_string()
	{
		$inputs = [
			'key_a' => ' Value_A',
			'key_b' => '22',
			'key_c' => '26',
		];
		$filters = [
			'key_c' => 'string',
		];
		$keys = 'key_b';
		//Return by one Key is Value not Array
		$result = 22;
		$this->assertEquals($result, (new ih())->run($inputs, $filters, $keys));

		$res = (new ih())->run($inputs, $filters, $keys);
		$this->assertIsNotArray($res);
		$this->assertIsInt($res);
		$this->assertIsNotString($res);
	}

	public function test_inputs__keys_is_string_not_valid()
	{
		$inputs = [
			'key_a' => ' Value_A',
			'key_b' => '22',
			'key_c' => '26',
		];
		$filters = [
			'key_c' => 'string',
		];
		$keys = 'invalid_key';
		//Return by one Key is Value not Array
		$result = null;
		$this->assertEquals($result, (new ih())->run($inputs, $filters, $keys));

		$res = (new ih())->run($inputs, $filters, $keys);
		$this->assertIsNotArray($res);
	}

	public function test_inputs__keys_is_string_with_pipe()
	{
		$inputs = [
			'key_a' => ' Value_A',
			'key_b' => '22',
			'key_c' => '26',
		];
		$filters = [
			'key_c' => 'string',
		];
		$keys = 'key_b|key_c';
		$result = [
			'key_b' => 22,
			'key_c' => '26',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters, $keys));

		$res = (new ih())->run($inputs, $filters, $keys);
		$this->assertIsInt($res['key_b']);
		$this->assertIsString($res['key_c']);
		$this->assertIsNotInt($res['key_c']);
	}
	
	public function test_inputs__keys_is_sequential_array()
	{
		$inputs = [
			'key_a' => ' Value_A',
			'key_b' => '22',
			'key_c' => '26',
		];
		$filters = [
			'key_c' => 'string',
		];
		$keys = [
			'key_b',
			'key_c',
		];
		$result = [
			'key_b' => 22,
			'key_c' => '26',
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters, $keys));

		$res = (new ih())->run($inputs, $filters, $keys);
		$this->assertIsInt($res['key_b']);
		$this->assertIsString($res['key_c']);
		$this->assertIsNotInt($res['key_c']);
	}

	public function test_inputs__keys_key_invalid()
	{
		$inputs = [
			'key_a' => ' Value_A',
			'key_b' => '22',
			'key_c' => '26',
		];
		$filters = [
			'key_c' => 'string',
		];
		$keys = [
			'key_b',
			'key_c_invalid',
		];
		$result = [
			'key_b' => 22,
		];
		$this->assertEquals($result, (new ih())->run($inputs, $filters, $keys));

		$res = (new ih())->run($inputs, $filters, $keys);
		$this->assertIsInt($res['key_b']);
		$this->assertIsNotString($res['key_b']);
	}

	public function test_inputs__keys_key_all_invalid()
	{
		$inputs = [
			'key_a' => ' Value_A',
			'key_b' => '22',
			'key_c' => '26',
		];
		$filters = [
			'key_c' => 'string',
		];
		$keys = [
			'key_b_invalid',
			'key_c_invalid',
		];
		$result = null;
		$this->assertEquals($result, (new ih())->run($inputs, $filters, $keys));

		$res = (new ih())->run($inputs, $filters, $keys);
		$this->assertIsNotArray($res);
	}

}
