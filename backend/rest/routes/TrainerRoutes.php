<?php

Flight::route('GET /trainers', function () {
    Flight::json(Flight::trainer_service()->get_all());
});

Flight::route('GET /trainers/specialty/@specialty', function ($specialty) {
    Flight::json(Flight::trainer_service()->get_by_specialty(urldecode($specialty)));
});

Flight::route('GET /trainers/@id:[0-9]+', function ($id) {
    Flight::json(Flight::trainer_service()->get_by_id($id));
});

Flight::route('POST /trainers', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::trainer_service()->add($data), 201);
});

Flight::route('PUT /trainers/@id:[0-9]+', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::trainer_service()->update($data, $id));
});

Flight::route('DELETE /trainers/@id:[0-9]+', function ($id) {
    Flight::trainer_service()->delete($id);
    Flight::json(['message' => 'Trainer deleted.']);
});