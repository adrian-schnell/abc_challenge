<?php

use App\Http\Requests\ChallengeDataRequest;

if (!function_exists('getColumnNameForMaxRequestSize')) {
	function getColumnNameForMaxRequestSize(): string
	{
		return getColumnName((new ChallengeDataRequest())->getCurrentRequestSize());
	}
}

if (!function_exists('getColumnName')) {
	function getColumnName(int $columnCount): string
	{
		$result = '';
		while ($columnCount > 0) {
			$columnCount--;
			$result = chr(65 + ($columnCount % 26)) . $result;
			$columnCount = intdiv($columnCount, 26);
		}

		return $result;
	}
}
