<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'release_date'
    ];

    public static function createBulk(array $movies)
    {
        return self::insert($movies);
    }

    public function cast()
    {
        return $this->hasMany(Cast::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function director()
    {
        return $this->hasOne(Director::class);
    }
}
