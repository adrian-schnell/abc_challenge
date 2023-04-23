<?php

use App\Http\Requests\ChallengeDataRequest;

if (!function_exists('getCharOfAlphabet')) {
	function getCharOfAlphabet(int $position = -1): string
	{
		$alphabet = range('A', 'Z');

		if ($position >= 1) {
			return $alphabet[$position];
		}

		$newRequest = new ChallengeDataRequest();

		return $alphabet[$newRequest->getCurrentRequestSize()];
	}
}
