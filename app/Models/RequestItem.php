<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function originalMessage(): HasOne
    {
        return $this->hasOne(RequestMessage::class, 'request_id', 'id')->oldest();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RequestCategory::class);
    }

}
