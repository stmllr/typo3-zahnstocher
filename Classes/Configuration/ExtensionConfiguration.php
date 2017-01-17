<?php
namespace Stmllr\Zahnstocher\Configuration;

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

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Extension Configuration
 */
class ExtensionConfiguration implements SingletonInterface {

	/**
	 * @var array
	 */
	protected $settings = array();

	/**
	 * @var string
	 */
	protected $fixturePath = '';

	/**
	 * @var string
	 */
	protected $mailboxPath = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['typo3_zahnstocher']);
		$this->initializeProperties();
	}

	/**
	 * Initializes class properties
	 *
	 * @return void
	 */
	protected function initializeProperties() {
		// fixturePath
		$fixturePath = $this->settings['fixturePath'];
		if (substr($fixturePath, 0, 1) !== '/' && substr($fixturePath, 0, 8) !== 'file:///') {
			$fixturePath = PATH_site . $fixturePath;
		}
		$this->fixturePath = rtrim($fixturePath, '/') . '/';

		// Create directories for fixtures if necessary
		$absoluteFixturePath = GeneralUtility::getFileAbsFileName($this->fixturePath);
		if (!@is_dir($absoluteFixturePath)) {
			GeneralUtility::mkdir_deep($absoluteFixturePath);
			$htaccessFile = $absoluteFixturePath . '/.htaccess';
			if (!empty($GLOBALS['TYPO3_CONF_VARS']['SYS']['generateApacheHtaccess']) && !file_exists($htaccessFile)) {
				GeneralUtility::writeFile($htaccessFile, 'Deny From All' . PHP_EOL);
			}
		}

		// mailboxPath
		$this->mailboxPath =  $this->fixturePath . $this->settings['mailboxFilename'];
	}

	/**
	 * Returns fixturePath
	 *
	 * @return string
	 */
	public function getFixturePath() {
		return $this->fixturePath;
	}

	/**
	 * Returns mailboxPath
	 *
	 * @return string
	 */
	public function getMailboxPath() {
		return $this->mailboxPath;
	}

}
