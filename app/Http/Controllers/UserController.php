<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(protected User $user) {}

    public function show(): JsonResponse
    {
        return $this->userResponse(auth()->tokenById(auth()->id()));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $user = $this->user->create($request->validated()['user']);

        $token = auth()->login($user);

        return $this->userResponse($token);
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        auth()->user()->update($request->validated()['user']);

        return $this->userResponse(auth()->tokenById(auth()->id()));
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if ($token = auth()->attempt($request->validated()['user'])) {
            return $this->userResponse($token);
        }

        throw ValidationException::withMessages(['error' => 'Invalid credentials.']);
    }

    protected function userResponse(?string $jwtToken): JsonResponse
    {
        return response()->json([
            'user' => [
                'token' => $jwtToken,
                ...auth()->user()->toArray()
            ]
        ]);
    }
}
