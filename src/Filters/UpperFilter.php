<?php

namespace Brunogab\InputHelper\Filters;

class UpperFilter
{
	/**
	 *  @param  string $val
	 *  @return string
	 */
	public function do($val)
	{
		return \is_string($val) ? \mb_strtoupper($val, \mb_detect_encoding($val)) : $val;
	}
}
