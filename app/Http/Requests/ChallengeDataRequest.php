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
			'pushupsDone'          => ['required', 'string', 'in:Yes,No,Ja,Nein'],
			'alcoholAbstinence'    => ['required', 'string', 'in:Yes,No,Ja,Nein'],
			'closedRings'          => ['required', 'string', 'in:Yes,No,Ja,Nein'],
			'validWorkoutDuration' => ['required', 'numeric'],
			'totalWorkoutDuration' => ['required', 'numeric'],
			'validWorkouts'        => ['required', 'numeric', 'min:0', 'int'],
			'totalWorkouts'        => ['required', 'numeric', 'min:0', 'int'],
			'noSugar'              => ['sometimes', 'nullable', 'string', 'in:Yes,No,Ja,Nein'],
			'noCarbs'              => ['sometimes', 'nullable', 'string', 'in:Yes,No,Ja,Nein'],
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
		return $this->validateYesNoAnswer($this->validated('pushupsDone'));
	}

	public function getAlcoholAbstinence(): string
	{
		return $this->validateYesNoAnswer($this->validated('alcoholAbstinence'));
	}

	public function getClosedRings(): string
	{
		return $this->validateYesNoAnswer();
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

	public function getNoSugar(): string
	{
		if (is_null($this->validated('noSugar'))) {
			return '';
		}
		return $this->validateYesNoAnswer($this->validated('noSugar'));

	}

	public function getNoCarbs(): string
	{
		if (is_null($this->validated('noCarbs'))) {
			return '';
		}
		return $this->validateYesNoAnswer($this->validated('noCarbs'));
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
			$this->getNoSugar(),
			$this->getNoCarbs(),
		];
	}

	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json([
			'errors' => $validator->errors(),
			'status' => true,
		], Response::HTTP_UNPROCESSABLE_ENTITY));
	}

	protected function validateYesNoAnswer(string $value): string
	{
		if (in_array($value, ['Yes', 'No'])) {
			return $value;
		}
		if ($value == 'Ja') {
			return 'Yes';
		}

		return 'No';
	}
}
