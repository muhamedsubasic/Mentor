<?php
/*
 * Entry point of the REST API.
 *
 *   HTTP request -> routes (presentation) -> services (business logic) -> dao (data access) -> MySQL
 */
require __DIR__ . '/vendor/autoload.php';

if (!file_exists(__DIR__ . '/config/config.php')) {
    http_response_code(500);
    exit('Missing backend/config/config.php – copy config.example.php to config.php and set your database credentials.');
}

require_once __DIR__ . '/rest/services/CategoryService.php';
require_once __DIR__ . '/rest/services/CourseService.php';
require_once __DIR__ . '/rest/services/TrainerService.php';
require_once __DIR__ . '/rest/services/PurchaseService.php';
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/ReviewService.php';
require_once __DIR__ . '/rest/services/CurrencyService.php';

// Register services once, use them in every route: Flight::course_service()->...
Flight::register('category_service', 'CategoryService');
Flight::register('course_service',   'CourseService');
Flight::register('trainer_service',  'TrainerService');
Flight::register('purchase_service', 'PurchaseService');
Flight::register('user_service',     'UserService');
Flight::register('review_service', 'ReviewService');
Flight::register('currency_service', 'CurrencyService');

/*
 * ONE place that turns every exception into a JSON response.
 *  - HttpException (thrown by services) -> its own status code and message (400, 404...)
 *  - anything else (PDOException, bugs)  -> 500 with a generic message.
 *    Never send stack traces, SQL or file paths to the client!
 */
Flight::map('error', function (Throwable $e) {
    if ($e instanceof HttpException) {
        $status = $e->get_status();
        $message = $e->getMessage();
    } else {
        $status = 500;
        $message = 'Something went wrong on the server. Please try again later.';
        error_log($e);   // details only in the server log
    }
    Flight::response()
        ->clearBody()
        ->status($status)
        ->header('Content-Type', 'application/json')
        ->write(json_encode(['error' => $message]))
        ->send();
});

Flight::map('notFound', function () {
    Flight::response()
        ->clearBody()
        ->status(404)
        ->header('Content-Type', 'application/json')
        ->write(json_encode(['error' => 'Endpoint not found.']))
        ->send();
});

Flight::route('GET /', function () {
    Flight::json(['app' => 'MentorCourses API', 'message' => 'The backend is running.']);
});

/* ---------- all entities, errors handled by Flight::map('error') above ---------- */

require_once __DIR__ . '/rest/routes/CategoryRoutes.php';
require_once __DIR__ . '/rest/routes/CourseRoutes.php';
require_once __DIR__ . '/rest/routes/TrainerRoutes.php';
require_once __DIR__ . '/rest/routes/PurchaseRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';
require_once __DIR__ . '/rest/routes/CurrencyRoutes.php';

Flight::start();