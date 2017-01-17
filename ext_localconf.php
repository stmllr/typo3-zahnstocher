<?php
defined('TYPO3_MODE') or die();

// Register command controller
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = \Stmllr\Zahnstocher\Command\FixturesCommandController::class;

// Register eID dispatcher
// usage: http://www.example.com/index.php?eID=tx_typo3_zahnstocher&controller=MailboxController&action=flush
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['tx_typo3_zahnstocher'] = 'EXT:' . $_EXTKEY . '/Classes/eID/Dispatcher.php';

// All mails will be stored in local fixture mbox file, instead of being send.
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport'] = 'mbox';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_mbox_file'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Stmllr\Zahnstocher\Configuration\ExtensionConfiguration::class)->getMailboxPath();
