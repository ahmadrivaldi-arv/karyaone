<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Department extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany
     */
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    /**
     * @return HasManyThrough
     */
    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, Position::class);
    }
}
