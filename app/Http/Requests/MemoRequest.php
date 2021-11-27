<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use App\Exceptions\ApiAuthException;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Memo;

class MemoRequest extends FormRequest
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
                    'folder_id' => 'nullable|string',
                    'title' => 'required|string',
                    'contents' => 'required',
                    'is_public' => 'nullable|boolean',
                ];
        }
    }

    public function checkAuthKey()
    {
        try {
            if (!$this->user()) {
                $uuid = Crypt::decryptString($this->get('key', null));
                if ($uuid !== $this->route('id')) {
                    throw new ApiAuthException('no auth');
                }
            }
        } catch (DecryptException $e) {
            throw new ApiAuthException('no auth');
        }
    }

    public function authorizeUser(Memo $memo)
    {
        if ($this->user() and ($memo->user_id !== $this->user()->id)) {
            throw new ApiAuthException('no auth', 403);
        }
    }
}
