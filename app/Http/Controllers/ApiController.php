<?php

namespace App\Http\Controllers;

use App\Exceptions\GoogleSheetException;
use App\Http\Requests\ChallengeDataRequest;
use App\Http\Service\GoogleApiService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as Response;

class ApiController extends Controller
{
	private GoogleApiService $apiService;

	public function __construct()
	{
		$this->apiService = new GoogleApiService();
	}

	public function receiveResults(ChallengeDataRequest $request): JsonResponse
	{
		/**
		 * if data already exist, update it
		 */
		if ($this->apiService->dataExists($request)) {
			try {
				$this->apiService->updateData($request);
			} catch (GoogleSheetException) {
				return response()->json([
					'message' => sprintf('Sorry %s, you never should see this error 🫣', $request->getName()),
				], Response::HTTP_BAD_REQUEST);
			}

			return response()->json([
				'message' => sprintf('Hey %s, your data for %s was updated!', $request->getName(), $request->getDate()),
			], Response::HTTP_OK);
		}
		// create new dataset
		$this->apiService->appendData($request);

		return response()->json([
			'message' => sprintf('Hey %s, sent new dataset for %s!', $request->getName(), $request->getDate()),
		], Response::HTTP_OK);
	}
}
