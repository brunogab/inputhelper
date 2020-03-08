<?php

namespace Brunogab\InputHelper\Filters;

class FloatFilter
{
	/**
	 *  @param  string $val
	 *  @return float
	 */
	public function do($val)
	{
		$val = \filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		return \floatval($val);
	}
}
