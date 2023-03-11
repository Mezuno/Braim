<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalTypeStoreRequest;
use App\Http\Requests\AnimalTypeUpdateRequest;
use App\Models\AnimalType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnimalController extends Controller
{
    public function viewType(int $id)
    {
        $animalType = AnimalType::find($id);
        $response = [
            'id' => $animalType->id,
            'type' => $animalType->type,
        ];
        return $response;
    }
    public function storeType(AnimalTypeStoreRequest $request)
    {
        $post = json_decode($request->getContent(), true);

        $validated = [
            'type' => $post['type'],
        ];

        AnimalType::insert($validated);
        $location = AnimalType::where('type', '=', $post['type'])->first();
        $response = json_encode([
            'id' => $location->id,
            'type' => $location->type,
        ]);
        return $response;
    }
    public function updateType(int $id, AnimalTypeUpdateRequest $request)
    {
        // Непонятно что делать с авторизацией

//        if (!Auth::check()) {
//            return new Response('Ошибка автризации 401', 401);
//        }
//        if (auth()->id() != $id) {
//            return new Response('Обновление не своего аккаунта 403', 403);
//        }

        if ($id <= 0 or $id === null) {
            return new Response('Типа животного с таким typeId не существует 400', 400);
        }

        $animalType = AnimalType::find($id);
        if (!$animalType) {
            return new Response('Тип животного не найден 403', 403);
        }

        $post = json_decode($request->getContent(), true);

        $animalType->type = $post['type'];
        $animalType->save();

        $response = json_encode([
            'id' => $animalType->id,
            'type' => $animalType->type,
        ]);

        return $response;
    }
    public function deleteType(int $id)
    {
        $animalType = AnimalType::findOrFail($id);

        if ($id <= 0 or $id === null) {
            return new Response('Типа животного с таким id не существует 400', 400);
        }

        // Тип животного связан с животным - добавить проверку



        // Непонятно что делать с авторизацией

//        if (!Auth::check()) {
//            return new Response('Ошибка автризации 401', 401);
//        }
//        if (auth()->id() != $id) {
//            return new Response('Обновление не своего аккаунта 403', 403);
//        }

        $animalType->delete();

        return '';
    }
}
