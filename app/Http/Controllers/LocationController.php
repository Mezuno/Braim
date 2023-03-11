<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocationController extends Controller
{
    public function view(int $id)
    {
        // Дописать ошибку 401

        if ($id <= 0) {
            return new Response('Неверный ID расположения', 400);
        }
        $location = Location::findOrFail($id);
        $response = json_encode([
            'id' => $location->id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);
        return $response;
    }

    public function store(LocationStoreRequest $request)
    {
        $post = json_decode($request->getContent(), true);

//        $location = new Location();
//        $location->latitude = $post['latitude'];
//        $location->longitude = $post['longitude'];
//        $location->save();

        $validated = [
            'latitude' => $post['latitude'],
            'longitude' => $post['longitude'],
        ];

//        $validated = $request->validated();
        Location::insert($validated);
        $location = Location::where('latitude', '=', $post['latitude'])->where('longitude', '=', $post['longitude'])->first();
        $response = json_encode([
            'id' => $location->id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);
        return $response;
    }

    public function update(int $id, LocationUpdateRequest $request)
    {
        // Непонятно что делать с авторизацией

//        if (!Auth::check()) {
//            return new Response('Ошибка автризации 401', 401);
//        }
//        if (auth()->id() != $id) {
//            return new Response('Обновление не своего аккаунта 403', 403);
//        }

        if ($id <= 0 or $id === null) {
            return new Response('Расположения с таким id не существует 400', 400);
        }

        $location = Location::find($id);
        if (!$location) {
            return new Response('Расположение не найдено 403', 403);
        }

        $post = json_decode($request->getContent(), true);

        $location->latitude = $post['latitude'];
        $location->longitude = $post['longitude'];
        $location->save();

        $response = json_encode([
            'id' => $location->id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);

        return $response;
    }

    public function delete(int $id)
    {
        $location = Location::findOrFail($id);

        if ($id <= 0 or $id === null) {
            return new Response('Расположение с таким id не существует 400', 400);
        }

        // Расположение связано с животным - добавить проверку



        // Непонятно что делать с авторизацией

//        if (!Auth::check()) {
//            return new Response('Ошибка автризации 401', 401);
//        }
//        if (auth()->id() != $id) {
//            return new Response('Обновление не своего аккаунта 403', 403);
//        }

        $location->delete();

        return '';
    }
}
