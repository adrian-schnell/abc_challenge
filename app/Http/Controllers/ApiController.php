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
                'message' => sprintf('Hey %s, deine Infos für den %s wurden aktualisiert!', $request->getName(),
                    $request->getDate()),
            ], Response::HTTP_OK, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        }
        // create new dataset
        $this->apiService->appendData($request);

        return response()->json([
            'message' => sprintf('Hey %s, Tagesupdate für den %s wurde übertragen!', $request->getName(),
                $request->getDate()),
        ], Response::HTTP_OK, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
    }

    public function getStatistics(): JsonResponse
    {
        $statisticRanges = [
            'current_day' => 'Overview!R3',
        ];

        $data = [];
        $errors = [];

        foreach ($statisticRanges as $key => $range) {
            $value = $this->apiService->getValueFromCell($range);

            if (is_null($value)) {
                $errors[] = sprintf('Could not read the value %s of the cell %s', $key, $range);
            } else {
                // Cast to a numeric type if possible using the helper
                $data[$key] = castToNumericIfPossible($value);
            }
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response()->json(['data' => $data]);
    }
}
