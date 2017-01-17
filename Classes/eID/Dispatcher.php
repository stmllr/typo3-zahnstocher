<?php
namespace Stmllr\Zahnstocher\eID;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

// Exit, if script is called directly (must be included via eID in index_ts.php)
if (!defined ('PATH_typo3conf')) die ('Could not access this script directly!');

/**
 * Dispatcher to launch test helpers
 */
class Dispatcher {

	/**
	 * Dependency injection container
	 *
	 * @var array
	 */
	protected $container = array();


	/**
	 * Constructor
	 *
	 * @param array $container
	 */
	public function __construct(array $container) {
		$this->container = $container;
	}

	/**
	 * Dispatches the validation requests
	 *
	 * @return void
	 */
	public function dispatch() {
		// Validate Parameters
		$controllerName = GeneralUtility::_GP('controller');
		$actionName = GeneralUtility::_GP('action');
		if (!$this->isParameterValid($controllerName) || !$this->isParameterValid($actionName)) {
			die('Invalid parameters');
		}

		// Instantiate controller and call action
		$controller = $this->getControllerInstance($controllerName);
		$action = $actionName . 'Action';
		if ($controller && method_exists($controller, $action)) {
			$controller->$action();
		} else {
			die('ControllerAction pair does not exist.');
		}
	}

	/**
	 * Validate given parameter
	 *
	 * @param string $parameter
	 * @return bool
	 */
	protected function isParameterValid($parameter) {
		$result = FALSE;
		if (preg_match('/^[a-zA-Z]+$/', $parameter)) {
			$result = TRUE;
		}

		return $result;
	}

	/**
	 * Returns an instance of the given controller
	 *
	 * @param string $controllerName
	 * @return object
	 */
	protected function getControllerInstance($controllerName) {
		$controller = NULL;
		$className = 'Stmllr\\Zahnstocher\\Controller\\' . $controllerName;
		if (class_exists($className)) {
			$controller = GeneralUtility::makeInstance($className, $this->container);
		}

		return $controller;
	}
}

// Stupid but lightweight dependency injection container
$container = array();
$container['extensionConfiguration'] = GeneralUtility::makeInstance(\Stmllr\Zahnstocher\Configuration\ExtensionConfiguration::class);

/** @var $dispatcher \Stmllr\Zahnstocher\eID\Dispatcher */
$dispatcher = GeneralUtility::makeInstance(\Stmllr\Zahnstocher\eID\Dispatcher::class, $container);
$dispatcher->dispatch();
