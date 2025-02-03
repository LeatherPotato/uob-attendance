<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Module extends Model
{
    use Notifiable;

    /**
    * @var list<string>
    */
    protected $fillable = [
        'name',
        'internal_module_id' //Not the ID of the university, but rather a unique id given by the uni
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'internal_module_id' => 'integer',
        ];
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_module');
    }
}
