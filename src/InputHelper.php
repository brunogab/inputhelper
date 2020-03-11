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
	 *	if !empty : string or sequential array
	 * 		foreach filters
	 * 			foreach inputs
	 * 				do filter / if filter not valid -> calculated type used as filter
	 *			}
	 *		}
	 *
	 *	if assoc array or empty
	 *		foreach inputs
	 *			foreach filters
	 *				do filter / if filter not valid/empty -> calculated type used as filter
	 * 			}
	 *		}
	 *		then
	 *		return (by given keys)
	 */
	public function run(array $inputs, $filters = [], $keys = [])
	{
		$filtered_inputs = [];
		$filters_is_assoc_array = true;
		if (empty($filters)) {
			$filters = [];
		}

		/**
		 * Checking Cases
		 */

		/** 1. IF Filter is NOT Empty */
		if (!empty($filters)) {
			//sequential array
			if ((\is_array($filters)) and (\array_keys($filters) === \range(0, \count($filters) - 1))) {
				$filters_is_assoc_array = false;
			}
			//string
			if (\is_string($filters)) {
				$filters = (false === \strpos($filters, '|')) ? [$filters] : $this->explode($filters);
				$filters_is_assoc_array = false;
			}

			if (!$filters_is_assoc_array) {
				foreach ($filters as $filter_value) { //loop filters
					foreach ($inputs as $input_key => $input_value) { //loop inputs
						$filterClassName = $this->createClassName($filter_value);

						if (class_exists($filterClassName)) {
							$filtered_inputs[$input_key] = (new $filterClassName)->do($input_value);
						} else {
							//filter not valid -> auto type-casting (calculated type used as filter)
							$filterClassName = $this->createClassName($this->calculateType($input_value));
							$filtered_inputs[$input_key] = (new $filterClassName)->do($input_value);
						}
					}
					$inputs = $filtered_inputs;
				}
			}
		}

		/** 2. IF Filter is Empty or Associative Array */
		if ($filters_is_assoc_array) {
			foreach ($inputs as $input_key => $input_value) { //loop inputs
				if (!array_key_exists($input_key, $filters)) {
					$filters[$input_key] = $this->calculateType($input_value);
				}

				$filter = $filters[$input_key];
				if (!is_array($filter)) {
					$filter = ($this->isClosure($filter)) ? [$filter] : $this->explode($filter);
				}
				//
				foreach ($filter as $filter_value) { //loop filter
					if ($this->isClosure($filter_value)) {
						$filtered_inputs[$input_key] = $input_value = $filter_value($input_value);
					} else {
						$filterClassName = $this->createClassName($filter_value);

						if (class_exists($filterClassName)) {
							$filtered_inputs[$input_key] = $input_value = (new $filterClassName)->do($input_value);
						} else {
							//def filter not valid -> auto type-casting (calculated type used as filter)
							$filterClassName = $this->createClassName($this->calculateType($input_value));
							$filtered_inputs[$input_key] = $input_value = (new $filterClassName)->do($input_value);
						}
					}
				}
			}
		}

		//get inputs by given keys
		$filtered_inputs = $this->getKeys($filtered_inputs, $keys);

		return $filtered_inputs;
	}

	/**
	 * Explode String by Pipe
	 *
	 * @param string $val
	 * @return array
	 */
	private function explode($val)
	{
		return explode('|', $val);
	}

	/**
	 * Check value of closure.
	 *
	 * @param mixed $val
	 * @return bool
	 */
	private function isClosure($val)
	{
		return $val instanceof Closure;
	}

	/**
	 * create Class name.
	 *
	 * @param string $val
	 * @return string
	 */
	private function createClassName($val)
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
			//if string was given without pipe |
			if ((is_string($keys)) and (false === strpos($keys, '|'))) {
				if (array_key_exists($keys, $inputs)) {
					//found -> return only this value from array
					return  $inputs[$keys];
				} else {
					//not found -> return null
					return null;
				}
			}

			//if string was given with pipe |
			if ((is_string($keys)) and (false !== strpos($keys, '|'))) {
				$keys = explode('|', $keys);
			}

			foreach ($keys as $key) {
				if (array_key_exists($key, $inputs)) {
					$ret_data[$key] = $inputs[$key];
				}
			}

			$inputs = $ret_data;
		}

		return (empty($inputs)) ? null : $inputs;
	}

	/**
	 *  Calculate Type of the given Value.
	 *
	 *  @param  mixed $val
	 *  @return string
	 */
	private function calculateType($val)
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
