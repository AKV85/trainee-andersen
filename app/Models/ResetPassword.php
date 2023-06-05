<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResetPassword extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='reset_password';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token'
    ];

    /**
     * Define the relationship with the User model.
     *
     * @return BelongsTo The user relationship.
     */    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
