<?php

namespace App\Http\Controllers;

use App\ApiRequest;
use App\Mail\CommentNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        // Валидация входных данных
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Если валидация не пройдена, вернуть ошибку с кодом 400
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

        // Вернуть успешный ответ с кодом 201 и сообщением о создании заявки
        return response()->json(['message' => 'Заявка успешно создана', 'request' => $newRequest], 201);
    }

    /**
     * Получает список заявок с возможностью фильтрации по статусу и дате создания.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Получение параметра 'status' из запроса для фильтрации по статусу
        $status = $request->input('status');

        // Получение параметров 'start_date' и 'end_date' из запроса
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Создание запроса для фильтрации по дате
        $query = ApiRequest::query();

        // Применение фильтра по статусу, если статус указан
        if ($status) {
            $query->where('status', $status);
        }

        // Применение фильтра по дате, если даты указаны
        if ($startDate && $endDate) {
            // Преобразование дат в объекты Carbon для правильной фильтрации
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);

            // Фильтрация по диапазону дат
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Получение списка заявок с учетом фильтрации
        $requests = $query->get();

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
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Обновление статуса и добавление комментария
        $task->update([
            'status' => 'Resolved', // Устанавливаем статус "Завершено"
            'comment' => $request->comment,
        ]);

        // Отправка уведомления на email пользователя
        Mail::to($task->email)->send(new CommentNotification($request->comment));

        return response()->json(['message' => 'Заявка успешно обновлена'], 200);
    }
}
