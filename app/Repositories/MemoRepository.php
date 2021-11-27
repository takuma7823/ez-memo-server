<?php

namespace App\Repositories;

use App\Http\Requests\MemoRequest;
use App\Models\Memo;
use Illuminate\Support\Str;
use App\Protocols\MemoRepositoryProtocol;

class MemoRepository implements MemoRepositoryProtocol
{
    /**
     * Memoモデル一覧取得
     *
     * @return void
     */
    public function index()
    {
        try {
            return Memo::where('is_archive', false)
            ->where('is_public', true)
            ->orWhere(function ($query) {
                $query->where('is_archive', false);
                $query->where('user_id', request()->user()->id ?? 0);
            })->orderBy('user_id', 'desc')
            ->latest()
            ->simplePaginate(30);
        } catch (\Exception) {
            dd('モデル作成失敗');
        }
    }
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

            return Memo::find($uuid);
        } catch (\Exception) {
            dd('モデル作成失敗');
        }
    }

    public function update(MemoRequest $request, Memo $memo) : Memo
    {
        try {
            $memo->folder_id = $request->get('folder_id', null);
            $memo->title = $request->get('title');
            $memo->contents = $request->get('contents');
            $memo->is_public = $request->get('is_public', false);
            $memo->update();

            return Memo::find($memo->id);
        } catch (\Exception) {
            dd('モデル作成失敗');
        }
    }
}

