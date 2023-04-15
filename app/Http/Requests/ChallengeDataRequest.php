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

	protected function prepareForValidation(): void
	{
		$this->merge([
			'validWorkoutDuration' => floatval(str_replace(',', '.', $this->get('validWorkoutDuration'))),
			'totalWorkoutDuration' => floatval(str_replace(',', '.', $this->get('totalWorkoutDuration'))),
		]);
	}

	public function rules(): array
	{
		return [
			'name'                 => ['required', 'string', 'in:Benny,Chris,Adrian'],
			'date'                 => ['required', 'date'],
			'stepCount'            => ['required', 'numeric', 'min:0', 'int'],
			'pushupsDone'          => ['required', 'string', 'in:Yes,No'],
			'workoutDone'          => ['required', 'string', 'in:Yes,No'],
			'alcoholConsumption'   => ['required', 'string', 'in:Yes,No'],
			'closedRings'          => ['required', 'string', 'in:Yes,No'],
			'validWorkoutDuration' => ['required', 'numeric'],
			'totalWorkoutDuration' => ['required', 'numeric'],
			'validWorkouts'        => ['required', 'numeric', 'min:0', 'int'],
			'totalWorkouts'        => ['required', 'numeric', 'min:0', 'int'],
		];
	}

	public function getName(): string
	{
		return $this->validated('name');
	}

	public function getDate(): string
	{
		return $this->validated('date');
	}

	public function getStepsCount(): int
	{
		return $this->validated('stepCount');
	}

	public function getPushupsDone(): string
	{
		return $this->validated('pushupsDone');
	}

	public function getWorkoutDone(): string
	{
		return $this->validated('workoutDone');
	}

	public function getAlcoholConsumption(): string
	{
		return $this->validated('alcoholConsumption');
	}

	public function getClosedRings(): string
	{
		return $this->validated('closedRings');
	}

	public function getValidWorkoutDuration(): float
	{
		return $this->validated('validWorkoutDuration');
	}

	public function getTotalWorkoutDuration(): float
	{
		return $this->validated('totalWorkoutDuration');
	}

	public function getValidWorkouts(): int
	{
		return $this->validated('validWorkouts');
	}

	public function getTotalWorkouts(): int
	{
		return $this->validated('totalWorkouts');
	}

	public function transformRequestToArray(): array
	{
		return [
			now()->format('d.m.Y H:i:s'),//"06.04.2023 12:10:54",
			$this->getName(),
			$this->getDate(),
			$this->getStepsCount(),
			$this->getPushupsDone(),
			$this->getWorkoutDone(),
			$this->getAlcoholConsumption(),
			$this->getValidWorkoutDuration(),
			$this->getClosedRings(),
			$this->getValidWorkouts(),
			$this->getTotalWorkouts(),
			$this->getTotalWorkoutDuration(),
		];
	}

	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json([
			'errors' => $validator->errors(),
			'status' => true,
		], 422));
	}
}
