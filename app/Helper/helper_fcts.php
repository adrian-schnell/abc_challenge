<?php

use App\Http\Requests\ChallengeDataRequest;

if (!function_exists('getCharOfAlphabet')) {
	function getCharOfAlphabet(): string
	{
		$alphabet = range('A', 'Z');

		return $alphabet[(new ChallengeDataRequest())->getCurrentRequestSize()];
	}
}
