<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi untuk list yang DIBUAT oleh user ini
    public function ownedTodoLists()
    {
        return $this->hasMany(TodoList::class, 'owner_id');
    }

    // Relasi untuk SEMUA list yang terhubung dengan user (sebagai pembuat maupun yang diundang)
    public function todoLists()
    {
        return $this->belongsToMany(TodoList::class, 'todo_list_user');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}