<?php

namespace Brunogab\InputHelper\Filters;

class TrimFilter
{
	/**
	 *  @param  string $val
	 *  @return string
	 */
	public function do($val)
	{
		//return \is_string($val) ? \trim($val) : $val;
		return \trim($val);
	}
}
