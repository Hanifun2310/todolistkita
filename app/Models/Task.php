<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['todo_list_id', 'title', 'completed_dates', 'target_month'];

    // Ubah method casts menjadi seperti ini:
    protected function casts(): array
    {
        return [
            'completed_dates' => 'array',
        ];
    }

    public function todoList()
    {
        return $this->belongsTo(TodoList::class);
    }
}