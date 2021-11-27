<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Folder;

class FolderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'DELETE':
                return [];
            default:
                return [
                    'name' => 'required|string',
                    'parent_id' => 'nullable|string',
                ];
        }
    }

    public function authorizeUser(Folder $folder)
    {
        if ($folder->user_id !== $this->user()->id){
            throw new ApiAuthException('no auth', 403);
        }
    }
}
