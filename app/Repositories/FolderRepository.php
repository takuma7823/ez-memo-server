<?php

namespace App\Repositories;

use App\Http\Requests\FolderRequest;
use App\Protocols\FolderRepositoryProtocol;
use Illuminate\Support\Str;
Use App\Models\Folder;

class FolderRepository implements FolderRepositoryProtocol
{
    public function create(FolderRequest $request) : Folder
    {
        try {
            $folder = new Folder();
            $uuid = Str::uuid()->toString();
            $folder->id = $uuid;
            $folder->name = $request->get('name');
            $folder->parent_id = $request->get('parent_id', null);
            $folder->user_id = $request->user()->id;
            $folder->save();

            return Folder::find($uuid);

        } catch (\Exception) {
            dd('モデル作成失敗');
        }
    }

    public function update(FolderRequest $request, Folder $folder) : Folder
    {
        try {
            $folder->name = $request->get('name');
            $folder->parent_id = $request->get('parent_id', null);
            $folder->update();

            return Folder::find($folder->id);
        } catch (\Exception) {
            dd('モデル作成失敗');
        }
    }
}