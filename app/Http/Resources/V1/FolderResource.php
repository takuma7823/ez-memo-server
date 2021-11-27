<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class FolderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'Folder',
            'attributes' => [
                'name' => $this->name,
                'user_id' => $this->user_id,
                'parent_id' => $this->parent_id,
            ],
            'relationships' => [
                'key' => $this->encryptUUID(),
            ],
            'links' => [
                'url' => 'http://ez-memo.test/api/v1/memos?key='.$this->encryptUUID(),
            ],
        ];
    }

    private function encryptUUID()
    {
        return Crypt::encryptString($this->id);
    }
}
