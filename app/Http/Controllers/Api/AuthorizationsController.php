<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        $user = User::query()->where('name', $request->name)->first();
        if (!$user) {
            $user = User::query()->create(
                $request->only([
                    'name',
                    'password'
                ])
            );
        }

        if (!Hash::check($request->password, $user->password)) {
            throw new AccessDeniedException('用户名或密码错误');
        }

        $token = $user->createToken('User Login');

        return $this->respondWithToken($token)->setStatusCode(201);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return UserResource::make($user);
    }

    public function destroy(Request $request)
    {
        $request->user()->token()->revoke();
        return response(null, 204);
    }

    protected function respondWithToken($token)
    {
        $model = $token->token;
        $model->expires_at = Carbon::now()->addDays(2);
        $model->save();

        return response()->json([
            'access_token' => $token->accessToken,
            'token_type' => 'Bearer',
            'expires_in' => $model->expires_at
        ]);
    }

}
