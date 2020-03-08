<?php

namespace Brunogab\InputHelper\Filters;

class StringFilter
{
	/**
	 *  @param  string $val
	 *  @return string
	 */
	public function do($val)
	{
		// $val = filter_var($val, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		// return (string) $val;

		return \filter_var($val, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	}
}
