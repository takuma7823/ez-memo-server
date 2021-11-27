<?php

namespace App\Protocols;

use App\Http\Requests\MemoRequest;
Use App\Models\Memo;

interface MemoRepositoryProtocol
{
    /**
     * Memoモデルの一覧取得に関する定義
     *
     * @return void
     */
    public function index();
    /**
     *Memoモデルの作成に関する定義
     *
     * @param MemoRequest $request
     * @return Memo
     */
    public function create(MemoRequest $request) : Memo;

    /**
     * Memoモデルの更新に関する定義
     *
     * @param MemoRequest $request
     * @param Memo $memo
     * @return Memo
     */
    public function update(MemoRequest $request, Memo $memo) : Memo;
}