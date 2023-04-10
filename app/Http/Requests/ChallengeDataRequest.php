<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChallengeDataRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'name'            => ['required', 'string', 'in:Benny,Chris,Adrian'],
//			'date'            => ['required', 'date_format:YYYY-MM-DD'],
//			'steps'           => ['required', 'numeric', 'min:0'],
//			'exerciseMinutes' => ['required', 'numeric', 'min:0'],
//			'pushups'         => ['required', 'string', 'in:YES,NO'],
//			'alcohol'         => ['required', 'string', 'in:YES,NO'],
//			'rings'           => ['required', 'string', 'in:YES,NO'],
		];
	}

	public function getName(): string
	{
		return $this->get('name');
	}
}
