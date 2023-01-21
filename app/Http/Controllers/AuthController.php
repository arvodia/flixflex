<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserBasicResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Validator;
use function event;
use function response;

class AuthController extends Controller {

    public function currentUserInfo() {
        return (Auth::check() && ($user = Auth::user())) ? new UserBasicResource($user) : response()->json(["message" => "Forbidden : you are not authorized"], 403);
    }

    public function authenticate(Request $request): JsonResponse {

        $rules = [
            'name' => ['required', 'max:255', 'exists:users,name'],
            'password' => ['required', 'string', 'max:255'],
        ];

        Validator::make($request->all(), $rules)->validate();

        $user = User::where('name', $request->name)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // login
            $token = $user->createToken(Str::orderedUuid()->toString())->plainTextToken;
            return response()->json(['token' => $token], 200);
        }

        throw ValidationException::withMessages([
                    'name' => ['The provided credentials are incorrect !'],
        ]);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'min:6', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $token = $user->createToken(Str::orderedUuid()->toString())->plainTextToken;
        return response()->json(['token' => $token], 200);
    }

}
