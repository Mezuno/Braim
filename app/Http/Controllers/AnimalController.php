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
use App\Models\User;
use App\Models\VisitedLocation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnimalController extends Controller
{
    public function view($id)
    {
        if ($id <= 0 or $id === null or !is_int((int)$id)) {
            return new Response('Неверный animalId животного', 400);
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

        $distinctPostAnimalTypes = array_unique($post['animalTypes']);
        $duplicatesPostAnimalTypes = array_diff_key($post['animalTypes'], $distinctPostAnimalTypes);
        if (!empty($duplicatesPostAnimalTypes)) {
            $duplicatesPostAnimalTypesString = "";
            foreach ($duplicatesPostAnimalTypes as $duplicatePostAnimalType) {
                $duplicatesPostAnimalTypesString .= $duplicatePostAnimalType.', ';
            }
            return new Response('Массив с animalTypes содержит дубликаты: '. $duplicatesPostAnimalTypesString, 409);
        }

        $animal = new Animal();
        $animal->animalTypes = json_encode($post['animalTypes']);
        $animal->weight = $post['weight'];
        $animal->length = $post['length'];
        $animal->height = $post['height'];
        $animal->gender = $post['gender'];
        $animal->chipperId = $post['chipperId'];
        $animal->chippingLocationId = $post['chippingLocationId'];
        $animal->visitedLocations = json_encode([$post['chippingLocationId']]);
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

    public function update($id, AnimalUpdateRequest $request)
    {
        if ($id <= 0 or $id === null or !is_int((int)$id)) {
            return new Response('Животного с таким animalId не существует 400', 400);
        }

        $animal = Animal::find($id);
        if (!$animal) {
            return new Response('Животное c animalId'. $id .' не найдено 404', 404);
        }

        $post = json_decode($request->getContent(), true);

        $animal->weight = $post['weight'];
        $animal->length = $post['length'];
        $animal->height = $post['height'];
        $animal->gender = $post['gender'];
        $animal->chipperId = $post['chipperId'];
        if ($animal->lifeStatus == 'ALIVE' and $post['lifeStatus'] == 'DEAD') {
            $animal->deathDateTime = now();
        }
        $animal->lifeStatus = $post['lifeStatus'];
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

    public function delete($id)
    {
        if ($id <= 0 or $id === null or !is_int((int)$id)) {
            return new Response('Животное с таким id не существует 400', 400);
        }

        $animal = Animal::find($id);

        if (!$animal) {
            return new Response('Животное с таким id не найдено 404', 404);
        }

        // Животное покинуло локацию чипирования, при этом есть другие посещенные точки - добавить проверку



        $animal->delete();

        return '';
    }
    public function viewType($id)
    {
        if ($id <= 0 or $id === null or !is_int((int)$id)) {
            return new Response('Тип животного с таким typeId не существует 400', 400);
        }

        $animalType = AnimalType::find($id);

        if (!$animalType) {
            return new Response('Тип животного с таким typeId не найден 404', 404);
        }

        $response = [
            'id' => $animalType->id,
            'type' => $animalType->type,
        ];
        return $response;
    }
    public function storeType(AnimalTypeStoreRequest $request)
    {
        $post = json_decode($request->getContent(), true);

        if (!isset($post['type']) or $post['type'] == "" or ctype_space($post['type']) or $post['type'] === null) {
            return new Response('поле type не может быть пустым', 400);
        }

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
    public function updateType($id, AnimalTypeUpdateRequest $request)
    {
        if ($id <= 0 or $id === null or !is_int((int)$id)) {
            return new Response('Типа животного с таким typeId не существует 400', 400);
        }

        $animalType = AnimalType::find($id);
        if (!$animalType) {
            return new Response('Тип животного не найден 403', 403);
        }

        $post = json_decode($request->getContent(), true);

        if (!isset($post['type']) or $post['type'] == "" or ctype_space($post['type']) or $post['type'] === null) {
            return new Response('поле type не может быть пустым', 400);
        }

        $animalType->type = $post['type'];
        $animalType->save();

        $response = json_encode([
            'id' => $animalType->id,
            'type' => $animalType->type,
        ]);

        return $response;
    }
    public function deleteType($id)
    {
        if ($id <= 0 or $id === null or !is_int((int)$id)) {
            return new Response('Типа животного с таким id не существует 400', 400);
        }

        $animalType = AnimalType::findOrFail($id);

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

        $animalTypes[array_search($post['oldTypeId'], $animalTypes)] = $post['newTypeId'];
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

        $newAnimalTypesArray = [];
        foreach ($animalTypes as $animalType) {
            if ($animalType != $typeId) {
                array_push($newAnimalTypesArray, $animalType);
            }
        }

        $animal->animalTypes = json_encode($newAnimalTypesArray, true);
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

    public function addLocation($animalId, $pointId)
    {
        if ($animalId == null or $animalId <= 0) {
            return new Response('Животное с таким id не существует 400', 400);
        }
        if ($pointId == null or $pointId <= 0) {
            return new Response('Точки локации с таким id не существует 400', 400);
        }

        $animal = Animal::findOrFail($animalId);
        Location::findOrFail($pointId);

        $visitedLocations = json_decode($animal->visitedLocations);

        if ($animal->lifeStatus == 'DEAD') {
            return new Response('Животное не может перемещаться, оно мертвое..', 400);
        }
        if (count($visitedLocations) == 1 and $visitedLocations['0'] == $pointId and $animal->chippingLocationId == $pointId) {
            return new Response('Животное находится в точке чипирования и никуда не перемещалось 400', 400);
        }
        if (end($visitedLocations) == $pointId) {
            return new Response('Животное находится в точке id'. $pointId .' на данный момент', 400);
        }

        array_push($visitedLocations, (int)$pointId);
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
    public function changeLocation($animalId, VisitedLocationChangeRequest $request)
    {
        if ($animalId == null or $animalId <= 0) {
            return new Response('Животного с таким id не сущетсвует 400', 400);
        }
        $animal = Animal::find((int)$animalId);
        if (!$animal) {
            return new Response('Животное с таким id не найдено 404', 404);
        }

        $post = json_decode($request->getContent(), true);

        if ($post['locationPointId'] == null or $post['locationPointId'] <= 0) {
            return new Response('Точки локации с таким id не сущетсвует 400', 400);
        }
        $location = Location::find($post['locationPointId']);
        if (!$location) {
            return new Response('Точки локации с таким id не найдено 404', 404);
        }

        if ($post['visitedLocationPointId'] == null or $post['visitedLocationPointId'] <= 0) {
            return new Response('Оъекта информации о посещении животным точки локации с таким id не сущетсвует 400', 400);
        }
        $visitedLocation = VisitedLocation::find($post['visitedLocationPointId']);
        if (!$visitedLocation) {
            return new Response('Оъекта информации о посещении животным точки локации с таким id не найдено 404', 404);
        }

        if ($visitedLocation->animalId != $animalId) {
            return new Response('Объект информации о посещении точки локации не относится к этому животному 404', 404);
        }

        $visitedLocation->locationPointId = $post['locationPointId'];
        $visitedLocation->save();

        $visitedLocations = json_decode($animal->visitedLocations);
        if ($visitedLocations[count($visitedLocations)-1] == $post['locationPointId']) {
            return new Response('Обновление точки на такую же точку 400', 400);
        }
        $visitedLocations[count($visitedLocations)-1] = $post['locationPointId'];
        $animal->visitedLocations = json_encode($visitedLocations, true);
        $animal->save();

        return json_encode([
            'id' => $visitedLocation->id,
            'dateTimeOfVisitLocationPoint' => $visitedLocation->dateTimeOfVisitLocationPoint,
            'locationPointId' => $visitedLocation->locationPointId,
        ], true);
    }
    public function removeLocation($animalId, $visitedPointId)
    {
        if ($animalId == null or $animalId <= 0) {
            return new Response('Животного с таким id не существует', 400);
        }
        if ($visitedPointId == null or $visitedPointId <= 0) {
            return new Response('Объект с информацией о посещенной точке локации не существует', 400);
        }

        $animal = Animal::find($animalId);
        if (!$animal) {
            return new Response('Животное с таким id не найдно 404', 404);
        }
        $visitedLocation = VisitedLocation::find($visitedPointId);
        if (!$visitedLocation) {
            return new Response('Объект с информацией о посещенной точке с таким id не найден 404', 404);
        }
        if ($visitedLocation->animalId != $animalId) {
            return new Response('Объект с информацией о посещенной точке локации не относится к этому животному', 404);
        }

        $visitedLocations = json_decode($animal->visitedLocations);
        if ($visitedLocation->locationPointId == $visitedLocations[1] and $visitedLocations[0] == $visitedLocations[2]) {
            VisitedLocation::where('animalId', '=', $animalId)->where('locationPointId', '=', $visitedLocations[2])->first()->delete();
            unset($visitedLocations[1]);
            unset($visitedLocations[2]);
        } else {
            unset($visitedLocations[array_search($visitedLocation->locationPointId, $visitedLocations)]);
        }
        $newVisitedLocationsArray = [];
        foreach ($visitedLocations as $visitedLocationTmp) {
            array_push($newVisitedLocationsArray, $visitedLocationTmp);
        }
        $animal->visitedLocations = json_encode($newVisitedLocationsArray, true);

        $animal->save();
        $visitedLocation->delete();
        return '';
    }
}
