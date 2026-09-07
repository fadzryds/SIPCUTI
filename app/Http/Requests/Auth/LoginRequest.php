<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * Login dapat menggunakan:
     * - Email
     * - Nomor handphone
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = trim($this->string('login')->toString());

        /*
        |--------------------------------------------------------------------------
        | Tentukan apakah login menggunakan email atau nomor handphone
        |--------------------------------------------------------------------------
        */

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {

            $credentials = [
                'email' => $login,
                'password' => $this->string('password')->toString(),
            ];

        } else {

            $credentials = [
                'phone' => $login,
                'password' => $this->string('password')->toString(),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Attempt Login
        |--------------------------------------------------------------------------
        */

        if (! Auth::attempt(
            $credentials,
            $this->boolean('remember')
        )) {

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => 'Email, nomor handphone, atau password yang Anda masukkan salah.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Login berhasil
        |--------------------------------------------------------------------------
        */

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => 'Terlalu banyak percobaan login. Silakan coba kembali dalam '
                . ceil($seconds / 60)
                . ' menit.',
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower(
                trim($this->string('login')->toString())
            )
            . '|'
            . $this->ip()
        );
    }
}