<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|max:60',
            'content' => 'required|max:5000'
        ];
    }
}
