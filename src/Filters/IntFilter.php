<?php

namespace Brunogab\InputHelper\Filters;

class IntFilter
{
	/**
	 *  @param  string $val
	 *  @return integer
	 */
	public function do($val)
	{
		$val = \filter_var($val, FILTER_SANITIZE_NUMBER_INT);
		return \intval($val);
	}
}
