<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChallengeDataRequest;
use App\Http\Service\GoogleApiService;
use Illuminate\Support\Collection;
use Revolution\Google\Sheets\Facades\Sheets;

class ApiController extends Controller
{
	private GoogleApiService $apiService;

	public function __construct()
	{
		$this->apiService = new GoogleApiService();
	}

	public function test()
	{
		$data = Sheets::spreadsheet(config('google.sheet_id'))
			->sheet(config('google.sheet_name'))
			->all();
		unset($data[0]);
		foreach ($data as $item) {
			return $item[0];
		}
	}

	public function receiveResults(ChallengeDataRequest $request): string
	{
		/**
		 * if data already exist, update it
		 */
		if ($this->apiService->dataExists($request)) {
			$this->apiService->updateData($request);

			return 'updated data';
		}
		// create new dataset
		$this->apiService->createData($request);

		return 'created new dataset';
	}
}
