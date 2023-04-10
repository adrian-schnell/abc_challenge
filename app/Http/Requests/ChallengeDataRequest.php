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
			'date'            => ['required', 'date_format:YYYY-MM-DD'],
			'steps'           => ['required', 'numeric', 'min:0', 'int'],
			'exerciseMinutes' => ['required', 'numeric', 'min:0', 'int'],
			'pushups'         => ['required', 'string', 'in:YES,NO'],
			'alcohol'         => ['required', 'string', 'in:YES,NO'],
			'rings'           => ['required', 'string', 'in:YES,NO'],
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
}
