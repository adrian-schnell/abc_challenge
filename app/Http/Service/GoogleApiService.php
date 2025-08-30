<?php

namespace App\Http\Service;

use App\Exceptions\GoogleSheetException;
use App\Http\Requests\ChallengeDataRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;

class GoogleApiService
{
	private ?int $foundIndex = null;

	public function dataExists(ChallengeDataRequest $data): bool
	{
		return $this->findIndex($data) != -1;
	}

	private function findIndex(ChallengeDataRequest $data): int
	{
		$sheetData = Sheets::spreadsheet(config('challenge.sheet_id'))
			->sheet(config('challenge.sheet_name'))
			->all();
		// remove header
		unset($sheetData[0]);
		foreach ($sheetData as $index => $item) {
			try {
				if (Carbon::parse($item[2])->diffInDays(Carbon::parse($data->getDate())) == 0
					&& $item[1] == $data->getName()) {
					$this->foundIndex = $index;

					return $index;
				}
			} catch (\Exception) {
			}
		}
		$this->foundIndex = -1;

		return -1;
	}

	/**
	 * @throws \App\Exceptions\GoogleSheetException
	 */
	public function updateData(ChallengeDataRequest $data): void
	{
		if (is_null($this->foundIndex)) {
			throw GoogleSheetException::runFindIndexFirst();
		}
		Sheets::spreadsheet(config('challenge.sheet_id'))
			->sheet(config('challenge.sheet_name'))
			->range(sprintf('A%s:%s%s', $this->foundIndex + 1, getColumnNameForMaxRequestSize(), $this->foundIndex + 1))
			->update([$data->transformRequestToArray()], 'USER_ENTERED');
	}

	public function appendData(ChallengeDataRequest $data): void
	{
		Sheets::spreadsheet(config('challenge.sheet_id'))
			->sheet(config('challenge.sheet_name'))
			->append([$data->transformRequestToArray()], 'USER_ENTERED');
	}

    /**
     * Retrieves the value from a specified cell range in the spreadsheet.
     *
     * @param  string  $range  The cell range to retrieve the value from.
     *
     * @return string|null The value of the cell, or null if no value is found or an exception occurs.
     */
	public function getValueFromCell(string $range): ?string
	{
		try {
			$values = Sheets::spreadsheet(config('challenge.sheet_id'))
				->range($range)
				->get();
			return $values->first()[0] ?? null;
		} catch (\Exception $e) {
			Log::error('Google Sheets API error in getValueFromCell: '.$e->getMessage());
			return null;
		}
	}
}
