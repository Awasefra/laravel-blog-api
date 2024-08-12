<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;
use App\Http\Resources\Auth\UserResource;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(LoginRequest $request)
    {
        try {
            // Search user that only has pasien_id
            if (!Auth::attempt($request->all())) {
                throw new \Exception("Login failed. Please check your email and password.", 401);
            }

            // Create token for the user
            $tokenObj = Auth::user()->createToken($request->email);

            $token = $tokenObj->accessToken;
            $expiredAt = $tokenObj->token->expires_at->format("Y-m-d H:i:s");

            return $this->SuccessResponse([
                "accessToken" => $token,
                "expiredAt" => $expiredAt,
                "user" => new UserResource(auth()->user())
            ], "Sucessfully login", 200);
        } catch (\Exception $e) {
            return $this->ErrorResponse($e->getMessage(), null, $e->getCode());
        }
    }

    public function me()
    {
        return $this->successResponse(new UserResource(auth()->user()));
    }

    public function logout()
    {
        $user = auth()->user();

        // Get logged in user token
        $token = $user->token();

        // Revoke the token
        // Use token repository from passport
        $tokenRepository = app(TokenRepository::class);

        $tokenRepository->revokeAccessToken($token->id);

        return $this->successResponse(true, "Sucessfully logged out");
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            
            // Get authenticated user
            $user = auth()->user();

            // Check if the old Password from request is correct
            if (!Hash::check($request->oldPassword, $user->password)) {
                throw new \Exception("Incorrect old password", 403);
            }

            // Update new password
            // Hash the new password before insert to DB
            $hashedPassword = Hash::make($request->newPassword);

            $user->update(["password" => $hashedPassword]);

            return $this->successResponse(null, "Sucessfully change the password.");
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage(), $e->getCode());
        }
    }
}
