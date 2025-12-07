<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'user_id']; // Add user_id if categories are per user

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Add this relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}