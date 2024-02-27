<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FilesActionRequest extends ParentIdBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'all' => 'nullable|bool',
            'ids.*' => [
                Rule::exists('files', 'id'),

                function ($attribute, $id, $fail) {
                    $file = File::query()
                        ->leftJoin('file_shares', 'file_shares.file_id', 'files.id')
                        ->where('files.id', $id)
                        ->where(function ($query) {
                            /** @var $query Builder */
                            $query->where('files.created_by', Auth::id())
                                ->orWhere('file_shares.user_id', Auth::id());
                        })
                        ->first();

                    if (!$file) {
                        $fail('Invalid ID "' . $id . '"');
                    }
                }
            ]
        ]);
    }
}
