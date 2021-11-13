<?php

namespace App\Repositories;

use App\Http\Requests\MemoRequest;
use App\Models\Memo;
use Illuminate\Support\Str;
use App\Protocols\MemoRepositoryProtocol;

class MemoRepository implements MemoRepositoryProtocol
{
    /**
     * Memoモデル作成
     *
     * @param MemoRequest $request
     * @return void
     */
    public function create(MemoRequest $request) : Memo
    {
        try {
            $uuid = Str::uuid()->toString();
            $memo = new Memo();
            $memo->id = $uuid;
            $memo->user_id = $request->user() ? $request->user()->id : null;
            $memo->folder_id = $request->get('folder_id', null);
            $memo->title = $request->get('title');
            $memo->contents = $request->get('contents');
            $memo->is_public = $request->get('is_public', false);

            $memo->save();
            $memo = Memo::find($uuid);

            return $memo;
        } catch (\Exception) {
            dd('モデル作成失敗');
        }
    }
}

