<?php

namespace CherryAudio\ApiInspector\Controllers;

use App\Models\User;

class TokensController extends Controller
{
    public function index(User $user)
    {
        $token = $user->createToken('API Impersonation');

        return [
            'id' => $token->token->id,
            'accessToken' => $token->accessToken,
        ];
    }
}
