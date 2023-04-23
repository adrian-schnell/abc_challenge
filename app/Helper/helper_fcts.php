<?php

use App\Http\Requests\ChallengeDataRequest;

if (!function_exists('getCharOfMaxRange')) {
	function getCharOfMaxRange(): string
	{
		$alphabet = range('A', 'Z');

		return $alphabet[(new ChallengeDataRequest())->getCurrentRequestSize()];
	}
}
