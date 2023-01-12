<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected fillable =[
        'title',
        'description',
        'image',
        'due_date',
        'current_status',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    
    public function lable()
    {
        return $this->hasMany(Lable::class);
    }
}