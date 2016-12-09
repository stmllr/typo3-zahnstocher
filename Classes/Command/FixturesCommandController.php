<?php
namespace Stmllr\Zahnstocher\Command;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Saltedpasswords\Salt\SaltFactory;

/**
 * Service for installation tasks
 */
class FixturesCommandController extends CommandController
{

    /**
     * Create a Backend User
     *
     * @param string $username
     * @param string $password
     * @param int $usergroup
     * @return void
     */
    public function createBeUserCommand($username, $password, $usergroup = 0)
    {
        if (empty($username)) {
            $this->outputLine('username must not be empty');
            return;
        }

        $table = 'be_users';
        if (0 < $this->getDatabaseConnection()->exec_SELECTcountRows(
            '*',
            $table,
            'username=' . $this->getDatabaseConnection()->fullQuoteStr($username, $table) . BackendUtility::deleteClause($table)
            )
        ) {
            $this->outputLine('Username already exists.');
        };
        
        $userFields = [
            'username' => $username,
            'password' => $this->getHashedPassword($password),
            'admin' => 0,
            'usergroup' => $usergroup,
            'options' => 3,
            'tstamp' => $GLOBALS['EXEC_TIME'],
            'crdate' => $GLOBALS['EXEC_TIME']
        ];
        if (false === $this->getDatabaseConnection()->exec_INSERTquery('be_users', $userFields)) {
            $this->outputLine('Failed to create BE user account: ' . $this->getDatabaseConnection()->sql_error());
        };
    }

    /**
     * This function returns a salted hashed key.
     *
     * @param string $password
     * @return string
     */
    protected function getHashedPassword($password)
    {
        $saltFactory = SaltFactory::getSaltingInstance(null, 'BE');
        return $saltFactory->getHashedPassword($password);
    }

    /**
     * Get database instance.
     * Will be initialized if it does not exist yet.
     *
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

}
