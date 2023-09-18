<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    /**
     * Отображает форму для создания нового API-токена.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('create-token');
    }

    /**
     * Создает новый API-токен для указанного пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user) {
            return redirect()->route('create.token')->with('error', 'Пользователь с указанным ID не найден.');
        }

        // Создание нового API-токена для пользователя
        $token = $user->createToken($request->name)->accessToken;

        return redirect()->route('create.token')->with('message', 'Токен успешно создан: ' . $token);
    }
}
