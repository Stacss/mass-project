<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function create()
    {
        return view('create-token');
    }

    public function store(Request $request)
    {
        // Проверка аутентификации пользователя (вы можете добавить проверку прав доступа)

        $user = User::find($request->user_id);

        if (!$user) {
            return redirect()->route('create.token')->with('error', 'Пользователь с указанным ID не найден.');
        }

        $token = $user->createToken($request->name)->accessToken;

        return redirect()->route('create.token')->with('message', 'Токен успешно создан: ' . $token);
    }
}
