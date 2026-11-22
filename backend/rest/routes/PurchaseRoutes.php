<?php

Flight::route('GET /purchases', function () {
    Flight::json(Flight::purchase_service()->get_all());
});

Flight::route('GET /purchases/revenue', function () {
    Flight::json(['total' => Flight::purchase_service()->get_total_revenue()]);
});

Flight::route('GET /purchases/user/@user_id:[0-9]+', function ($user_id) {
    Flight::json(Flight::purchase_service()->get_by_user($user_id));
});

Flight::route('GET /purchases/@id:[0-9]+', function ($id) {
    Flight::json(Flight::purchase_service()->get_by_id($id));
});

Flight::route('POST /purchases', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::purchase_service()->add($data), 201);
});

Flight::route('PUT /purchases/@id:[0-9]+', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::purchase_service()->update($data, $id));
});

Flight::route('DELETE /purchases/@id:[0-9]+', function ($id) {
    Flight::purchase_service()->delete($id);
    Flight::json(['message' => 'Purchase refunded.']);
});