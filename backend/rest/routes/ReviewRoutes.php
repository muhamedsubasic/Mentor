<?php

Flight::route('GET /reviews', function () {
    Flight::json(Flight::review_service()->get_all());
});

Flight::route('GET /reviews/@id:[0-9]+', function ($id) {
    Flight::json(Flight::review_service()->get_by_id($id));
});

Flight::route('GET /reviews/course/@course_id:[0-9]+', function ($course_id) {
    Flight::json(Flight::review_service()->get_by_course($course_id));
});

Flight::route('POST /reviews', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::review_service()->add($data), 201);
});

Flight::route('PUT /reviews/@id:[0-9]+', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::review_service()->update($data, $id));
});

Flight::route('DELETE /reviews/@id:[0-9]+', function ($id) {
    Flight::review_service()->delete($id);
    Flight::json(['message' => 'Review deleted.']);
});