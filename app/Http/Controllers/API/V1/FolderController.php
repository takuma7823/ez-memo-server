<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Folder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Http\Requests\FolderRequest;
use App\Protocols\FolderRepositoryProtocol;
use App\Http\Resources\V1\FolderResource;

class FolderController extends Controller
{
    public function __construct(FolderRepositoryProtocol $folderRepository)
    {
        $this->folderRepository = $folderRepository;
    }
    /**
     * フォルダー一覧取得API
     *
     * @return void
     */
    public function folders()
    {
        return response()->json(Folder::where('user_id', request()->user()->id)
        ->where('parent_id', null)
        ->with(['children'])
        ->get());
    }

    public function store(FolderRequest $request)
    {
        return (new FolderResource($this->folderRepository->create($request)))
        ->response()->setStatusCode(201);
    }

    public function update(FolderRequest $request, Folder $folder)
    {
        $request->authorizeUser($folder);
        return (new FolderResource($this->folderRepository->update($request, $folder)))
        ->response()->setStatusCode(202);
    }

    public function delete(FolderRequest $request, Folder $folder)
    {
        $request->authorizeUser($folder);
        $folder->delete();

        return response()->json(['status' => 'ok'], 204);
    }
}
