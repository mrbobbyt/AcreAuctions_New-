<?php
declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string email
 * @property string token
 */
class PasswordResets extends Model
{

    protected $table = 'password_resets';

    protected $fillable = [
        'email', 'token', 'created_at'
    ];

    public $timestamps = false;


    /**
     * Get belongs user model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'email', 'email');
    }

}
