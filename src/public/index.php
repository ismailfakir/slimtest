<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
spl_autoload_register(function ($classname) {
    require ("../classes/" . $classname . ".php");
});

/*
 * configuration
 */
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host'] = "localhost";
$config['db']['user'] = "dagenslunch";
$config['db']['pass'] = "open123";
$config['db']['dbname'] = "dagenslunchapp";

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("set names utf8");
    return $pdo;
};

$app->get('/person/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    
    $mapper = new PersonsMapper($this->db);
    $person = $mapper->getPersonById($id);
    
    $newResponse = $response->withJson($person);
    return $newResponse;
});

$app->get('/persons', function (Request $request, Response $response) {
    $this->logger->addInfo("Person list");
    $mapper = new PersonsMapper($this->db);
    $persons = $mapper->getPersons();
    $personsVal = $persons;

    //$response->getBody()->write(var_export($persons, true));
    $newResponse = $response->withJson($personsVal);
    return $newResponse;
});

$app->post('/person/new', function (Request $request, Response $response) {
    $data = $request->getParsedBody();

    $person_data = []; //P_Id, LastName, FirstName, Address, City
    $person_data['P_Id'] = (int) filter_var($data['P_Id'], FILTER_SANITIZE_NUMBER_INT);

    $this->logger->addInfo("Something interesting happened" . $person_data['P_Id']);
    echo $person_data['P_Id'];
    $person_data['LastName'] = filter_var($data['LastName'], FILTER_SANITIZE_STRING);
    $person_data['FirstName'] = filter_var($data['FirstName'], FILTER_SANITIZE_STRING);
    $person_data['Address'] = filter_var($data['Address'], FILTER_SANITIZE_STRING);
    $person_data['City'] = filter_var($data['City'], FILTER_SANITIZE_STRING);
    // work out the component

    $person1 = new PersonsEntity($person_data);
    $person_mapper = new PersonsMapper($this->db);
    $person_mapper->save($person1);
    
    $newResponse=$response->withRedirect("/persons");

    return $newResponse;
});

$app->run();
