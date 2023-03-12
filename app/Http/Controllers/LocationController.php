<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalStoreRequest;
use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocationController extends Controller
{
    public function view($id)
    {
        if ($id <= 0 or $id == null or !is_int((int)$id)) {
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

        $validated = [
            'latitude' => $post['latitude'],
            'longitude' => $post['longitude'],
        ];

        if (Location::where('latitude', '=', $validated['latitude'])->where('longitude', '=', $validated['longitude'])->first()) {
            return new Response('Точка локации с такими latitude и longitude уже существует', 409);
        }

        Location::insert($validated);
        $location = Location::where('latitude', '=', $validated['latitude'])->where('longitude', '=', $validated['longitude'])->first();
        $response = json_encode([
            'id' => $location->id,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);
        return $response;
    }

    public function update($id, LocationUpdateRequest $request)
    {
        // Непонятно что делать с авторизацией

//        if (!Auth::check()) {
//            return new Response('Ошибка автризации 401', 401);
//        }

        if ($id <= 0 or $id == null or !is_int((int)$id)) {
            return new Response('Точка локации с таким pointId не существует 400', 400);
        }

        $location = Location::find($id);
        if (!$location) {
            return new Response('Точка лоакции с таким pointId не найдена 404', 404);
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

    public function delete($id)
    {
        if ($id <= 0 or $id == null or !is_int((int)$id)) {
            return new Response('Расположение с таким id не существует 400', 400);
        }

        $location = Location::find($id);
        if (!$location) {
            return new Response('Точка лоакции с таким pointId не найдена 404', 404);
        }

        // Расположение связано с животным - добавить проверку



        // Непонятно что делать с авторизацией

//        if (!Auth::check()) {
//            return new Response('Ошибка автризации 401', 401);
//        }

        $location->delete();

        return '';
    }
}
