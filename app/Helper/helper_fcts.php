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

if (!function_exists('castToNumericIfPossible')) {
	/**
	 * Casts a string to an integer or float if it is numeric.
	 *
	 * @param  string|null  $value  The input string.
	 * @return int|float|string|null The casted value or the original value.
	 */
	function castToNumericIfPossible(string|null $value): int|float|string|null
	{
		if (is_null($value) || !is_numeric($value)) {
			return $value;
		}

		// Cast to float if it contains a decimal point, otherwise to int.
		return str_contains($value, '.') ? (float) $value : (int) $value;
	}
}
