<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'slug'];

    public function author() {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function post_images() {
        return $this->hasOne('App\Models\PostImage');
    }

    public function category() {
        return $this->belongsTo('App\Models\Category','category_id','id');
    }



}
