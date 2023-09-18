<?php

namespace App\Http\Controllers;

use App\ApiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RequestController extends Controller
{
    /**
     * Создать новую заявку пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Проверка входных данных на валидность
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Если входные данные не прошли валидацию, вернуть ошибку с кодом 400 (Bad Request)
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Создание заявки
        $newRequest = ApiRequest::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'status' => 'Active', // Устанавливаем статус "Active" по умолчанию
            'message' => $request->input('message'),
        ]);

        // Вернуть успешный ответ с кодом 201 (Created) и сообщением о создании заявки
        return response()->json(['message' => 'Заявка успешно создана', 'request' => $newRequest], 201);
    }
}
