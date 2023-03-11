<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalChangeTypeRequest;
use App\Http\Requests\AnimalStoreRequest;
use App\Http\Requests\AnimalTypeStoreRequest;
use App\Http\Requests\AnimalTypeUpdateRequest;
use App\Http\Requests\AnimalUpdateRequest;
use App\Http\Requests\VisitedLocationChangeRequest;
use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\Location;
use App\Models\VisitedLocation;
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

    public function addType(int $animalId, int $typeId)
    {
        $animal = Animal::findOrFail($animalId);

        AnimalType::findOrFail($typeId);

        $animalTypes = json_decode($animal->animalTypes);

        if (in_array($typeId, $animalTypes)) {
            return new Response('У животного id'. $animalId .' уже есть тип животного '. $typeId, 409);
        }

        array_push($animalTypes, $typeId);

        $animal->animalTypes = json_encode($animalTypes, true);
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

    public function changeType(int $animalId, AnimalChangeTypeRequest $request)
    {
        $animal = Animal::findOrFail($animalId);

        $post = json_decode($request->getContent(), true);

        AnimalType::findOrFail($post['oldTypeId']);
        AnimalType::findOrFail($post['newTypeId']);

        $animalTypes = json_decode($animal->animalTypes);

        if (!in_array($post['oldTypeId'], $animalTypes)) {
            return new Response('У животного id'. $animalId .' нету типа животного '. $post['oldTypeId'], 404);
        }
        if (in_array($post['newTypeId'], $animalTypes)) {
            if (in_array($post['oldTypeId'], $animalTypes)) {
                return new Response('У животного id' . $animalId . ' уже есть тип животного ' . $post['newTypeId'] . ' и тип животного '. $post['oldTypeId'], 409);
            }
            return new Response('У животного id'. $animalId .' уже есть тип животного '. $post['newTypeId'], 409);
        }

        unset($animalTypes[array_search($post['oldTypeId'], $animalTypes)]);
        array_push($animalTypes, $post['newTypeId']);
        $animal->animalTypes = $animalTypes;
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

    public function removeType(int $animalId, int $typeId)
    {
        $animal = Animal::findOrFail($animalId);

        AnimalType::findOrFail($typeId);

        $animalTypes = json_decode($animal->animalTypes);

        if (!in_array($typeId, $animalTypes)) {
            return new Response('У животного id'. $animalId .' нету типа животного '. $typeId, 404);
        }
        if (count($animalTypes) == 1) {
            return new Response('У животного id'. $animalId .' только один тип животного и это '. $typeId, 400);
        }

        unset($animalTypes[array_search($typeId, $animalTypes)]);

        $animal->animalTypes = json_encode($animalTypes, true);
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

    public function addLocation(int $animalId, int $pointId)
    {
        $animal = Animal::findOrFail($animalId);
        Location::findOrFail($pointId);

        $visitedLocations = json_decode($animal->visitedLocations);

        if ($animal->lifeStatus == 'DEAD') {
            return new Response('Животное не может перемещаться, оно мертвое..', 400);
        }
        if (end($visitedLocations) == $pointId) {
            return new Response('Животное находится в точке id'. $pointId .' на данный момент', 400);
        }

        array_push($visitedLocations, $pointId);
        $animal->visitedLocations = json_encode($visitedLocations, true);
        $animal->save();

        VisitedLocation::insert([
            'animalId' => $animalId,
            'locationPointId' => $pointId,
            'dateTimeOfVisitLocationPoint' => now()
        ]);

        $visitedLocation = VisitedLocation::where('animalId', '=', $animalId)->where('locationPointId', '=', $pointId)->first();

        $response = json_encode([
            'id' => $visitedLocation->id,
            'dateTimeOfVisitLocationPoint' => $visitedLocation->dateTimeOfVisitLocationPoint,
            'locationPointId' => $visitedLocation->locationPointId,
        ], true);

        return $response;
    }
    public function changeLocation(int $animalId, VisitedLocationChangeRequest $request)
    {
        $animal = Animal::findOrFail($animalId);

        $post = json_decode($request->getContent(), true);

        Location::findOrFail($post['locationPointId']);

        $visitedLocation = VisitedLocation::findOrFail($post['visitedLocationPointId']);

        $visitedLocation->locationPointId = $post['locationPointId'];
        $visitedLocation->save();

        $visitedLocations = json_decode($animal->visitedLocations);
        $visitedLocations[count($visitedLocations)-1] = $post['locationPointId'];
        $animal->visitedLocations = json_encode($visitedLocations, true);
        $animal->save();

        return json_encode([
            'id' => $visitedLocation->id,
            'dateTimeOfVisitLocationPoint' => $visitedLocation->dateTimeOfVisitLocationPoint,
            'locationPointId' => $visitedLocation->locationPointId,
        ], true);
    }
    public function removeLocation(int $animalId, int $visitedPointId)
    {
        $animal = Animal::findOrFail($animalId);
        $visitedLocation = VisitedLocation::findOrFail($visitedPointId);

        // Тип животного связан с животным - добавить проверку

        if ($animalId == null or $animalId <= 0) {
            return new Response('Животного с таким id не существует', 400);
        }
        if ($visitedPointId == null or $visitedPointId <= 0) {
            return new Response('Объект с информацией о посещенной точке локации не существует', 400);
        }
        if ($visitedLocation->animalId != $animalId) {
            return new Response('Объект с информацией о посещенной точке локации не относится к этому животному', 404);
        }


        $visitedLocation->delete();

        return '';
    }
}
