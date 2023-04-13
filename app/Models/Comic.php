<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Database\Eloquent\SoftDeletes;  

class Comic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'prolog',
        'author',
        'eps'
    ];

    public function writer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }
}
