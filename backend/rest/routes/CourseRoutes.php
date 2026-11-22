<?php

Flight::route('GET /courses', function () {
    Flight::json(Flight::course_service()->get_all());
});

Flight::route('GET /courses/search', function () {
    $q = Flight::request()->query['q'] ?? '';
    Flight::json(Flight::course_service()->search($q));
});

Flight::route('GET /courses/category/@category_id:[0-9]+', function ($category_id) {
    Flight::json(Flight::course_service()->get_by_category($category_id));
});

Flight::route('GET /courses/trainer/@trainer_id:[0-9]+', function ($trainer_id) {
    Flight::json(Flight::course_service()->get_by_trainer($trainer_id));
});

Flight::route('GET /courses/@id:[0-9]+/reviews', function ($id) {
    Flight::json(Flight::review_service()->get_by_course($id));
});

Flight::route('GET /courses/@id:[0-9]+', function ($id) {
    Flight::json(Flight::course_service()->get_by_id($id));
});

Flight::route('POST /courses', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::course_service()->add($data), 201);
});

Flight::route('PUT /courses/@id:[0-9]+', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::course_service()->update($data, $id));
});

Flight::route('DELETE /courses/@id:[0-9]+', function ($id) {
    Flight::course_service()->delete($id);
    Flight::json(['message' => 'Course deleted.']);
});