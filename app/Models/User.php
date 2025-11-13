<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable  implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'codice_fiscale',
        'nome',
        'cognome',
        'data_nascita',
        'luogo_nascita',
        'telefono',
        'indirizzo_residenza',
        'nazionalita',
        'sesso',
        'email',
        'password',
        'is_admin', // Aggiunto campo is_admin per verificare se l'utente è un amministratore
        'role',
        ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'is_admin' => 'boolean',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
     protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
     ];

     public function hasRole(string $role): bool
    {
        return strtolower($this->role) === strtolower($role);
    }

     public function getFullNameAttribute()
    {
        return $this->nome . ' ' . $this->cognome;
    }

    public function getNameAttribute(): string
    {
        return $this->full_name;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->is_admin === true;
    }

    public function isUser(): bool
    {
        return $this->role === 'user' || $this->is_admin === false;
    }

    public function getRoleIconAttribute(): string
    {
        return match($this->role ?? ($this->is_admin ? 'admin' : 'user')) {
            'admin' => 'fas fa-crown',
            'user' => 'fas fa-user',
            default => 'fas fa-user-circle'
        };
    }

    public function getRoleColorAttribute(): string
    {
        return match($this->role ?? ($this->is_admin ? 'admin' : 'user')) {
            'admin' => 'text-warning',
            'user' => 'text-primary',
            default => 'text-secondary'
        };
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailCustom());
    }

}
