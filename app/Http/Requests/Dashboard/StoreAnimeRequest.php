<?php

namespace App\Http\Requests\Dashboard;

use App\Http\Middleware\IsAdmin;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAnimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'         => 'required|string|max:255',
            'description'   => 'required',
            'type'          => 'required|in:series,movie,live_action',
            'status'        => 'required|in:ongoing,completed',
            'studio'        => 'required|string|max:255',
            'director'      => $this->type === 'series' ? 'nullable' : 'nullable|string|max:255',
            'release_date'  => 'required|date',
            'thumbnail'     => 'required|image|mimes:jpeg,png,jpg',
            'genre_ids'     => 'required|array',
            'genre_ids.*'   => 'exists:genres,id',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * If the 'release_date' field is present, it converts the field to a standard date string format.
     *
     * @return void
     */

    public function prepareForValidation()
    {
        if ($this->has('release_date')) {
            $this->merge([
                'release_date' => Carbon::parse($this->release_date)->toDateString(),
            ]);
        }
    }
}
