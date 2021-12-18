<?php

abstract class Controller {
	/**
	 * Process a Get request and calls apropriate function
	 *
	 * @param array $params
	 *
	 * @return string $response
	 */
	abstract public function processGetRequest(array $params);
	/**
	 * Process a Post request and calls apropriate function
	 *
	 * @param array $params
	 *
	 * @return string $response
	 */
	abstract public function processPostRequest(array $params);
	/**
	 * Process a Put request and calls apropriate function
	 *
	 * @param array $params
	 *
	 * @return string $response
	 */
	abstract public function processPutRequest(array $params);
	/**
	 * Process a Delete request and calls apropriate function
	 *
	 * @param array $params
	 *
	 * @return string $response
	 */
	abstract public function processDeleteRequest(array $params);
}
