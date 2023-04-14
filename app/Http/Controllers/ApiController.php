<?php

namespace App\Http\Controllers;

use App\Exceptions\GoogleSheetException;
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

	public function receiveResults(ChallengeDataRequest $request): string
	{
		/**
		 * if data already exist, update it
		 */
		if ($this->apiService->dataExists($request)) {
			try {
				$this->apiService->updateData($request);
			} catch(GoogleSheetException) {
				return 'you never should see this..';
			}

			return 'updated data';
		}
		// create new dataset
		$this->apiService->appendData($request);

		return 'created new dataset';
	}
}
