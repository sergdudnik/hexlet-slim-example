<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

function generate($count)
{
    $numbers = range(1, 100);
    shuffle($numbers);

    $faker = \Faker\Factory::create();
    $faker->seed(1);
    $companies = [];
    for ($i = 0; $i < $count; $i++) {
        $companies[] = [
            'id' => $numbers[$i],
            'name' => $faker->company,
            'phone' => $faker->phoneNumber
        ];
    }

    return $companies;
}

$companies = generate(100);

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $response->write('go to the /companies');
});

// BEGIN (write your solution here)
$app->get('/companies', function ($request, $response) use ($companies) {
    $page = $request->getQueryParam('page', 1);
    $per = $request->getQueryParam('per', 5);
    $pagedArray = array_chunk($companies, $per);
    $nthPage = $pagedArray[$page - 1];
    $response->getBody()->write(json_encode($nthPage));
    return $response
            ->withHeader('Content-Type', 'application/json');
});
$app->get('/courses/{id}', function ($request, $response, array $args) {
    $id = $args['id'];
    return $response->write("Course id: {$id}");
});
// END

$app->run();
