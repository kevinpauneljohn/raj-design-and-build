<?php

namespace App\Http\Requests;

use App\Services\PhaseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePhaseRequest extends FormRequest
{

    private $remaining_percentage = 0;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('add phase');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(PhaseService $phaseService): array
    {
        $this->remaining_percentage = $phaseService->check_phase_remaining_percentage($this->project_id);
        return [
            'name' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'numeric', 'min:0',
                Rule::prohibitedIf(
                    $this->percentage > $this->remaining_percentage,
                )],
            'project_id' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'percentage.prohibited' => 'The percentage must not be greater than '.$this->remaining_percentage.'%',
        ];
    }
}
