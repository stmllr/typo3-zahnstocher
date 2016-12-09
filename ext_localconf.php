<?php
defined('TYPO3_MODE') or die();

// Register command controller
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = \Stmllr\Zahnstocher\Command\FixturesCommandController::class;
