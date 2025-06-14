<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new user.
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Login a user.
     *
     * @param array $credentials
     * @return string
     * @throws ValidationException
     */
    public function login(array $credentials): string
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Logout a user.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::user()->tokens()->delete();
    }

    /**
     * Get the authenticated user's profile.
     *
     * @return User
     */
    public function getProfile(): User
    {
        return Auth::user();
    }
} 