<?php

namespace Brunogab\InputHelper\Filters;

class LowerFilter
{
	/**
	 *  @param  string $val
	 *  @return string
	 */
	public function do($val)
	{
		return \is_string($val) ? \mb_strtolower($val, \mb_detect_encoding($val)) : $val;
	}
}
