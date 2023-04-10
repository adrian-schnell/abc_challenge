<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChallengeDataRequest;
use Illuminate\Http\Request;

class ApiController extends Controller
{
	public function receiveResults(ChallengeDataRequest $request): string
	{
		return $request->getName();
    }
}
