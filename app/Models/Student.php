<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'contact',
        'email',
        'degree_id',
        'user_account_id',
    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class)->withDefault([
            'title' => 'No degree assigned',
        ]);
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ])));
    }

    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(UserAccounts::class, 'user_account_id', 'id');
    }
}
