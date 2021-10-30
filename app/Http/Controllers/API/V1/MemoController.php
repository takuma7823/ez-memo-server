<?php

namespace App\Http\Controllers\API\V1;

use App\Exceptions\ApiAuthException;
use App\Http\Controllers\Controller;
use App\Models\Memo;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class MemoController extends Controller
{
    /**
     * メモ参照API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiAuthException
     */
    public function view(Request $request)
    {
        $key = $request->get('key', null);

        if (!$key) {
            throw new ApiAuthException('no auth');
        }

        try {
            $uuid = Crypt::decryptString($key);
            $memo = Memo::findOrFail($uuid);
            return response()->json($memo);
        } catch (DecryptException $e) {
            throw new ApiAuthException('no auth');
        }
    }

    /**
     * メモ作成API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'folder_id' => 'nullable|integer',
            'title' => 'required|string',
            'contents' => 'required',
            'is_public' => 'nullable|boolean',
        ]);

        $uuid = Str::uuid()->toString();

        $memo = new Memo();
        $memo->id = $uuid;
        $memo->user_id = $request->user() ? $request->user()->id : null;
        $memo->folder_id = $request->get('folder_id', null);
        $memo->title = $request->get('title');
        $memo->contents = $request->get('contents');
        $memo->is_public = $request->get('is_public', false);

        $memo->save();

        $encryptUUID = Crypt::encryptString($uuid);

        return response()->json([
            'key' => $encryptUUID,
            'url' => 'http://ez-memo.test/api/v1/memos?key='.$encryptUUID,
        ]);
    }
}