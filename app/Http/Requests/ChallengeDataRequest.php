<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

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
			'name'                 => ['required', 'string', sprintf('in:%s', config('challenge.challengers'))],
			'date'                 => ['required', 'date'],
			'stepCount'            => ['required', 'numeric', 'min:0', 'int'],
			'pushupsDone'          => ['required', 'string', 'in:Yes,No'],
			'alcoholAbstinence'    => ['required', 'string', 'in:Yes,No'],
			'closedRings'          => ['required', 'string', 'in:Yes,No'],
			'validWorkoutDuration' => ['required', 'numeric'],
			'totalWorkoutDuration' => ['required', 'numeric'],
			'validWorkouts'        => ['required', 'numeric', 'min:0', 'int'],
			'totalWorkouts'        => ['required', 'numeric', 'min:0', 'int'],
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

	public function getStepsCount(): int
	{
		return $this->get('stepCount');
	}

	public function getPushupsDone(): string
	{
		return $this->get('pushupsDone');
	}

	public function getAlcoholAbstinence(): string
	{
		return $this->get('alcoholAbstinence');
	}

	public function getClosedRings(): string
	{
		return $this->get('closedRings');
	}

	public function getValidWorkoutDuration(): float
	{
		return $this->get('validWorkoutDuration');
	}

	public function getTotalWorkoutDuration(): float
	{
		return $this->get('totalWorkoutDuration');
	}

	public function getValidWorkouts(): int
	{
		return $this->get('validWorkouts');
	}

	public function getTotalWorkouts(): int
	{
		return $this->get('totalWorkouts');
	}

	public function transformRequestToArray(): array
	{
		return [
			now()->format('d.m.Y H:i:s'), // format e.g. "06.04.2023 12:10:54",
			$this->getName(),
			$this->getDate(),
			$this->getStepsCount(),
			$this->getPushupsDone(),
			$this->getAlcoholAbstinence(),
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
		], Response::HTTP_UNPROCESSABLE_ENTITY));
	}
}
