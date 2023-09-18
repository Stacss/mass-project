<?php

namespace App\Http\Controllers;

use App\ApiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RequestController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

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

        // нужно добавить уведомление на почту

        return response()->json(['message' => 'Заявка успешно создана'], 201);
    }
}
