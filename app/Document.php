<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['title', 'description'];
    protected $appends = ['file_link'];

    public function file()
    {
        return $this->hasOne('App\File');
    }

    public function getFileLinkAttribute()
    {
        return asset('storage/img/'. $this->file()->pluck('filename')[0]);
    }
}
