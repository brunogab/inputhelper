<?php

namespace Brunogab\InputHelper\Filters;

class BoolFilter
{
	/**
	 *  @param  string $val
	 *  @return boolean
	 */
	public function do($val)
	{
		return filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}
}
