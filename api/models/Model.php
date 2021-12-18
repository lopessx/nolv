<?php

abstract class Model {
	/**
	 * Get row from the database
	 *
	 * @param array $params
	 *
	 * @return boolean|array $return
	 */
	abstract public static function get(array $params);
	/**
	 * Insert new row in the database
	 *
	 * @param array $params
	 *
	 * @return boolean|string $return
	 */
	abstract public static function insert(array $params);
	/**
	 * Update a row in the database
	 *
	 * @param array $params
	 *
	 * @return boolean|string $return
	 */
	abstract public static function update(array $params);
	/**
	 * Delete a row in the database
	 *
	 * @param array $params
	 *
	 * @return boolean|string $return
	 */
	abstract public static function delete(array $params);
}
