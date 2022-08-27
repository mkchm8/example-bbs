<?php

namespace App\Http\Requests\Web\Comment;

use App\Domain\Entities\Comment;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'max:' . Comment::TITLE_MAX_LENGTH,
            ],
            'body' => [
                'required',
                'max:' . Comment::MAX_LENGTH,
            ],
        ];
    }
}
