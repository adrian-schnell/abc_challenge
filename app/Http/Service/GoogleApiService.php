<?php

namespace App\Http\Service;

use App\Http\Requests\ChallengeDataRequest;
use Carbon\Carbon;
use Revolution\Google\Sheets\Facades\Sheets;

class GoogleApiService
{
	public function dataExists(ChallengeDataRequest $data): bool
	{
		return $this->findIndex($data) != -1;
	}

	/**
	 * [▼
	 * 0 => "06.04.2023 12:10:54"
	 * 1 => "Benny"
	 * 2 => "02.04.2023"
	 * 3 => "9157"
	 * 4 => "No"
	 * 5 => "Yes"
	 * 6 => "No"
	 * ]
	 */
	private function findIndex(ChallengeDataRequest $data): int
	{
		$sheetData = Sheets::spreadsheet(config('google.sheet_id'))
			->sheet(config('google.sheet_name'))
			->all();
		// remove header
		unset($sheetData[0]);
		foreach ($sheetData as $index => $item) {
			try {
				if (Carbon::parse($item[2])->diffInDays(Carbon::parse($data->getDate())) == 0
					&& $item[1] == $data->getName()) {
					return $index;
				}
			} catch (\Exception) {
			}
		}

		return -1;
	}

	public function updateData(ChallengeDataRequest $data): void
	{
	}

	public function createData(ChallengeDataRequest $data): void
	{
	}
}
