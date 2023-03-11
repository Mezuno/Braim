<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function view(int $id)
    {
        // Дописать ошибку 401

        if ($id <= 0) {
            return new Response('Неверный ID пользователя', 400);
        }
        $user = User::findOrFail($id);
        $response = json_encode([
            'id' => $user->id,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'email' => $user->email,
        ]);
        return $response;
    }

    public function search(Request $request)
    {
        // Работает абсолютно неверно, заменить на scopeSearch или подобное

        $search = [
            'firstName' => $request->input('firstName') ?? null,
            'lastName' => $request->input('lastName') ?? null,
            'email' => $request->input('email') ?? null,
        ];

        $users = User::all()->where('firstName', '=', $search['firstName'])
                            ->where('lastName', '=', $search['lastName'])
                            ->where('email', '=', $search['email']);

//        Второй вариант
//
//        $users = User::all();
//        dump($users);
//        $users = User::filter(function($user) use ($search) {
//            return stripos($user['firstName'], $search['firstName']) !== false
//                && stripos($user['lastName'], $search['lastName']) !== false
//                && stripos($user['email'], $search['email']) !== false;
//        });

        return $users;
    }

    public function update(int $id, UserUpdateRequest $request)
    {
        // Непонятно что делать с авторизацией

//        if (!Auth::check()) {
//            return new Response('Ошибка автризации 401', 401);
//        }
//        if (auth()->id() != $id) {
//            return new Response('Обновление не своего аккаунта 403', 403);
//        }
        if ($id <= 0 or $id === null) {
            return new Response('Пользователя с таким id не существует 400', 400);
        }

        $user = User::find($id);
        if (!$user) {
            return new Response('Аккаунт не найден 403', 403);
        }

        $post = json_decode($request->getContent(), true);
        if (!Hash::check($post['password'], $user->password)) {
            return new Response('Неверный пароль', 401);
        }

        $user->firstName = $post['firstName'];
        $user->lastName = $post['lastName'];
        $user->email = $post['email'];
        $user->save();


        $response = json_encode([
            'id' => $user->id,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'email' => $user->email,
        ]);

        // На всякий случай пусть тут полежит
//        dump(UserResource::collection(User::findOrFail($id)));
//        $post = json_encode($request->all(), JSON_UNESCAPED_UNICODE);

        return $response;
    }

    public function delete(int $id)
    {
        $user = User::findOrFail($id);

        if ($id <= 0 or $id === null) {
            return new Response('Пользователя с таким id не существует 400', 400);
        }

        // Аккаунт связан с животным - добавить проверку



        // Непонятно что делать с авторизацией

//        if (!Auth::check()) {
//            return new Response('Ошибка автризации 401', 401);
//        }
//        if (auth()->id() != $id) {
//            return new Response('Обновление не своего аккаунта 403', 403);
//        }

        $user->delete();

        return '';
    }
}
