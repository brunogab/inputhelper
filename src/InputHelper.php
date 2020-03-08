<?php declare(strict_types=1);

namespace Brunogab\InputHelper;

use Closure;

final class InputHelper
{
	/**
	* @var string Namespace for Filter
	*/
	private $filterNamespace = 'Brunogab\\InputHelper\\Filters\\';

	public function __construct()
	{
	}

	/**
	 * Run.
	 *
	 * @param array $inputs
	 * @param mixed $filters
	 * @param mixed $keys
	 * @return array
	 *
	 *	foreach inputs
	 * 		if filter for input not found -> calculate type (calculated type used as filter)
	 * 		get filter
	 *		foreach filters
	 *			do filter on input-data
	 * 		}
	 *	}
	 *	then
	 *	return or return by given key/keys
	 */
	public function run($inputs, $filters = [], $keys = [])
	{
		$final_inputs = [];
		$filter_string = null;

		if (is_string($filters)) {
			$filter_string = $filters;
			$filters = [];
		}

		//loop inputs
		foreach ($inputs as $input_key => $input_value) {
			//if filter for input not found -> calculate type (calculated type used as filter)
			if (!empty($filter_string)) {
				$filters[$input_key] = $filter_string;
			} elseif (!array_key_exists($input_key, $filters)) {
				$filters[$input_key] = $this->calculateType($input_value);
			}

			//get filter
			$filter = $filters[$input_key];
			if (!is_array($filter)) {
				$filter = ($this->isClosure($filter)) ? [$filter] : explode('|', $filter);
			}

			//loop filter
			foreach ($filter as $filter_value) {
				// Closure filter
				if ($this->isClosure($filter_value)) {
					$final_inputs[$input_key] = $input_value = $filter_value($input_value);
				} else {
					//def filter
					$filterClassName = $this->createClassName($filter_value);

					if (class_exists($filterClassName)) {
						$final_inputs[$input_key] = $input_value = (new $filterClassName)->do($input_value);
					} else {
						//def filter not valid -> calculate type (calculated type used as filter)
						$filterClassName = $this->createClassName($this->calculateType($input_value));
						$final_inputs[$input_key] = $input_value = (new $filterClassName)->do($input_value);
					}
				}
			}
		}

		//get inputs by given keys
		$final_inputs = $this->getKeys($final_inputs, $keys);

		return $final_inputs;
	}

	/**
	 * Check value of closure.
	 *
	 * @param mixed $val
	 * @return bool
	 */
	protected function isClosure($val)
	{
		return $val instanceof Closure;
	}

	/**
	 * create Class name.
	 *
	 * @param string $val
	 * @return string
	 */
	protected function createClassName($val)
	{
		return $this->filterNamespace . ucfirst($val) . 'Filter';
	}

	/**
	 * Get keys from inputs.
	 *
	 * @param array $inputs
	 * @param mixed $keys
	 * @return array
	 */
	private function getKeys(array $inputs, $keys = null)
	{
		$ret_data = [];
		if (!empty($keys)) {
			if (is_string($keys)) {
				$keys = [$keys];
			}

			foreach ($keys as $key) {
				if (array_key_exists($key, $inputs)) {
					$ret_data[$key] = $inputs[$key];
				}
			}

			$inputs = $ret_data;
		}

		return $inputs;
	}

	/**
	 *  Calculate Type of the given Value.
	 *
	 *  @param  mixed $val
	 *  @return string
	 */
	public function calculateType($val)
	{
		$ret = 'string';

		if (is_bool(filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
			$ret = 'bool';
		} elseif (filter_var($val, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_OCTAL) !== false) {
			$ret = 'int';
		} elseif (is_float(filter_var($val, FILTER_VALIDATE_FLOAT))) {
			$ret = 'float';
		} elseif (is_string($val)) {
			$ret = 'string';
		}
		return $ret;
	}
}
