<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TodoList extends Model
{
    protected $fillable = ['owner_id', 'title', 'share_token'];

    // Otomatis membuat share_token unik saat TodoList baru dibuat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($todoList) {
            $todoList->share_token = Str::random(10);
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'todo_list_user');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}