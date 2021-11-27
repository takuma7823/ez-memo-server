<?php

namespace App\Protocols;

use App\Http\Requests\FolderRequest;
Use App\Models\Folder;

interface FolderRepositoryProtocol
{
    public function create(FolderRequest $request) : Folder;

    public function update(FolderRequest $request, Folder $folder) : Folder;
}