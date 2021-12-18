<?php

include_once __DIR__ . '/../../controller/ClienteController.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
$requestMethod = $_SERVER['REQUEST_METHOD'];
$inputJson = file_get_contents('php://input');
$clientController = new ClienteController();

switch ($requestMethod) {
	case 'GET':
		echo $clientController->processGetRequest($_GET);

		break;
	case 'POST':
		break;
	case 'PUT':
		break;
	case 'DELETE':
		break;
	default:
		break;
}

// all of our endpoints start with /person
// everything else results in a 404 Not Found
/* if ($uri[1] !== 'person') {
	header("HTTP/1.1 404 Not Found");
	exit();
}

// the user id is, of course, optional and must be a number:
$userId = null;
if (isset($uri[2])) {
	$userId = (int) $uri[2];
}
 */

// pass the request method and user ID to the PersonController and process the HTTP request:
/* $controller = new PersonController($dbConnection, $requestMethod, $userId);
$controller->processRequest(); */
