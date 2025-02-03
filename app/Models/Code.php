<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Code extends Model
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'time',
        'is_active',
        'user_id', //foreign key
        'module_id', //foreign key
        'course_id' //foreign key
    ];

        /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'time' => 'datetime',
            'is_active' => 'boolean',
            'user_id' => 'integer',
            'module_id' => 'integer',
            'course_id' => 'integer'
        ];
    }
}
