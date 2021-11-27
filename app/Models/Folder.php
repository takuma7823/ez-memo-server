<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    protected $keyType ='string';
    /**
     * 子フォルダー
     *
     * @return void
     */
    public function children()
    {
        return $this->hasMany(self::class,'parent_id', 'id');
    }
}
