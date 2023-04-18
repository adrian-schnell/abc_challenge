<?php

namespace App\Http\Service;

use App\Exceptions\GoogleSheetException;
use App\Http\Requests\ChallengeDataRequest;
use Carbon\Carbon;
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
			->range('A' . ($this->foundIndex + 1) . ':M' . $this->foundIndex + 1)
			->update([$data->transformRequestToArray()], 'USER_ENTERED');
	}

	public function appendData(ChallengeDataRequest $data): void
	{
		Sheets::spreadsheet(config('challenge.sheet_id'))
			->sheet(config('challenge.sheet_name'))
			->append([$data->transformRequestToArray()], 'USER_ENTERED');
	}
}
