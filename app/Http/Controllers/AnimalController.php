<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalStoreRequest;
use App\Http\Requests\AnimalTypeStoreRequest;
use App\Http\Requests\AnimalTypeUpdateRequest;
use App\Http\Requests\AnimalUpdateRequest;
use App\Models\Animal;
use App\Models\AnimalType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnimalController extends Controller
{
    public function view(int $id)
    {
        // Дописать ошибку 401

        if ($id <= 0) {
            return new Response('Неверный ID расположения', 400);
        }

        $animal = Animal::findOrFail($id);

        $response = json_encode([
            'id' => $animal->id,
            'animalTypes' => $animal->animalTypes,
            'weight' => $animal->weight,
            'length' => $animal->length,
            'height' => $animal->height,
            'gender' => $animal->gender,
            'lifeStatus' => $animal->lifeStatus,
            'chippingDateTime' => $animal->chippingDateTime,
            'chipperId' => $animal->chipperId,
            'chippingLocationId' => $animal->chippingLocationId,
            'visitedLocations' => $animal->visitedLocations,
            'deathDateTime' => $animal->deathDateTime,
        ]);
        return $response;
    }

    public function store(AnimalStoreRequest $request)
    {
        $post = json_decode($request->getContent(), true);

        $validated = [
            'animalTypes' => json_encode($post['animalTypes']),
            'weight' => $post['weight'],
            'length' => $post['length'],
            'height' => $post['height'],
            'gender' => $post['gender'],
            'chipperId' => $post['chipperId'],
            'chippingLocationId' => $post['chippingLocationId'],
        ];

        Animal::insert($validated);
        $animal = Animal::where('chipperId', '=', $post['chipperId'])->first();

        $response = json_encode([
            'id' => $animal->id,
            'animalTypes' => $animal->animalTypes,
            'weight' => $animal->weight,
            'length' => $animal->length,
            'height' => $animal->height,
            'gender' => $animal->gender,
            'lifeStatus' => $animal->lifeStatus,
            'chippingDateTime' => $animal->chippingDateTime,
            'chipperId' => $animal->chipperId,
            'chippingLocationId' => $animal->chippingLocationId,
            'visitedLocations' => $animal->visitedLocations,
            'deathDateTime' => $animal->deathDateTime,
        ]);
        return $response;
    }

    public function update(int $id, AnimalUpdateRequest $request)
    {

        if ($id <= 0 or $id === null) {
            return new Response('Животного с таким animalId не существует 400', 400);
        }

        $animal = Animal::find($id);
        if (!$animal) {
            return new Response('Животное не найдено 403', 403);
        }

        $post = json_decode($request->getContent(), true);

        $animal->weight = $post['weight'];
        $animal->length = $post['length'];
        $animal->height = $post['height'];
        $animal->gender = $post['gender'];
        $animal->lifeStatus = $post['lifeStatus'];
        $animal->chipperId = $post['chipperId'];
        $animal->chippingLocationId = $post['chippingLocationId'];
        $animal->save();

        $response = json_encode([
            'id' => $animal->id,
            'animalTypes' => $animal->animalTypes,
            'weight' => $animal->weight,
            'length' => $animal->length,
            'height' => $animal->height,
            'gender' => $animal->gender,
            'lifeStatus' => $animal->lifeStatus,
            'chippingDateTime' => $animal->chippingDateTime,
            'chipperId' => $animal->chipperId,
            'chippingLocationId' => $animal->chippingLocationId,
            'visitedLocations' => $animal->visitedLocations,
            'deathDateTime' => $animal->deathDateTime,
        ]);

        return $response;
    }

    public function delete(int $id)
    {
        $animal = Animal::findOrFail($id);

        if ($id <= 0 or $id === null) {
            return new Response('Животное с таким id не существует 400', 400);
        }

        // Животное покинуло локацию чипирования, при этом есть другие посещенные точки - добавить проверку



        $animal->delete();

        return '';
    }
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



        $animalType->delete();

        return '';
    }
}
