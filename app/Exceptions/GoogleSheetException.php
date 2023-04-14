<?php

namespace App\Exceptions;

use Exception;
class GoogleSheetException extends Exception
{
	public static function runFindIndexFirst(): self
	{
		return new self('you need to run "findIndex()" before');
	}
}
