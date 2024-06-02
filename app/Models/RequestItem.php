<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestItem extends Model
{
    use HasFactory;

    protected $table = 'requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'theme',
        'file_path',
        'closed_at'
    ];


    public function messages(): HasMany
    {
        return $this->hasMany(RequestMessage::class, 'request_id', 'id');
    }

}
