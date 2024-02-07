<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => 'required|max:1000',
        ];
    }
}
