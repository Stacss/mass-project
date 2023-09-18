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

    /**
     * Получает список заявок с возможностью фильтрации по статусу.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Получение параметра 'status' из запроса для фильтрации по статусу
        $status = $request->input('status');

        // Получение списка заявок с учетом фильтрации, если статус указан
        $requests = $status ? ApiRequest::where('status', $status)->get() : ApiRequest::all();

        return response()->json(['requests' => $requests]);
    }

    /**
     * Обновляет заявку, устанавливая статус "Завершено" и добавляя комментарий.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Проверка наличия заявки с указанным ID
        $task = ApiRequest::find($id);


        if (!$task) {
            return response()->json(['error' => 'Заявка не найдена'], 404);
        }

        // Валидация данных запроса
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'mes' => $request->all()->toArray()], 400);
        }

        // Обновление статуса и добавление комментария
        $task->update([
            'status' => 'Resolved', // Устанавливаем статус "Завершено"
            'comment' => $request->input('comment'), // Присваиваем комментарий
        ]);

        // Отправка уведомления на email пользователя (необходимо реализовать)

        return response()->json(['message' => 'Заявка успешно обновлена'], 200);
    }


}
