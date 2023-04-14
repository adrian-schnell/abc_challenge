<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

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
			'date'            => ['required', 'date'],
			'steps'           => ['required', 'numeric', 'min:0', 'int'],
			'exerciseMinutes' => ['required', 'numeric', 'min:0', 'int'],
			'pushups'         => ['required', 'string', 'in:Yes,No'],
			'alcohol'         => ['required', 'string', 'in:Yes,No'],
			'rings'           => ['required', 'string', 'in:Yes,No'],
		];
	}

	public function getName(): string
	{
		return $this->get('name');
	}

	public function getDate(): string
	{
		return $this->get('date');
	}

	public function getSteps(): int
	{
		return $this->get('steps');
	}

	public function getExerciseMinutes(): int
	{
		return $this->get('exerciseMinutes');
	}

	public function getPushups(): bool
	{
		return $this->get('pushups');
	}

	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json([
			'errors' => $validator->errors(),
			'status' => true,
		], 422));
	}
}
