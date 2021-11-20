<?php

namespace App\Http\Controllers\API\V1;

use App\Exceptions\ApiAuthException;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemoRequest;
use App\Models\Memo;
use App\Protocols\MemoRepositoryProtocol;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Resources\V1\MemoResource;


class MemoController extends Controller
{
    public function __construct(MemoRepositoryProtocol $memoRepository)
    {
        $this->memoRepository = $memoRepository;
    }

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
            return (new MemoResource(Memo::findOrFail(Crypt::decryptString($key))))
            ->response()
            ->setStatusCode(200);
        } catch (DecryptException $e) {
            throw new ApiAuthException('no auth');
        }
    }

    /**
     * メモ作成API
     * @param MemoRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(MemoRequest $request)
    {
        return (new MemoResource($this->memoRepository->create($request)))
        ->response()->setStatusCode(201);
    }

    /**
     * メモ更新API
     *
     * @param MemoRequest $request
     * @param string $id
     * @return void
     */
    public function update(MemoRequest $request, string $id)
    {
        $request->checkAuthKey();
        $memo = Memo::findOrFail($id);
        $request->authorizeUser($memo);

        return (new MemoResource($this->memoRepository->update($request, $memo)))
        ->response()
        ->setStatusCode(202);
    }

    /**
     * メモ削除API
     *
     * @param MemoRequest $request
     * @param string $id
     * @return void
     */
    public function delete(MemoRequest $request, string $id)
    {
        $request->checkAuthKey();
        $memo = Memo::findOrFail($id);
        $request->authorizeUser($memo);
        $memo->delete();

        return response()->json(['status' => 'OK'], 204);
    }
}