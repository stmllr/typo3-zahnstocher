<?php
namespace Stmllr\Zahnstocher\Log\Writer;

/**
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

use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Writer\WriterInterface;

/**
 * FileWriter which extends the core FileWriter,
 * using print_r instead of json to format its data.
 */
class FileWriter extends \TYPO3\CMS\Core\Log\Writer\FileWriter {

	/**
	 * Writes the log record
	 *
	 * @param LogRecord $record Log record
	 * @return WriterInterface $this
	 * @throws \RuntimeException
	 */
	public function writeLog(LogRecord $record) {
		$timestamp = date('r', (int)$record->getCreated());
		$levelName = LogLevel::getName($record->getLevel());
		$data = '';
		$recordData = $record->getData();
		if (!empty($recordData)) {
			// According to PSR3 the exception-key may hold an \Exception
			// Since json_encode() does not encode an exception, we run the _toString() here
			if (isset($recordData['exception']) && $recordData['exception'] instanceof \Exception) {
				$recordData['exception'] = (string)$recordData['exception'];
			}
			$data = '- ' . print_r($recordData, TRUE);
		}

		$message = sprintf(
			'%s [%s] request="%s" component="%s": %s %s',
			$timestamp,
			$levelName,
			$record->getRequestId(),
			$record->getComponent(),
			$record->getMessage(),
			$data
		);

		if (FALSE === fwrite(self::$logFileHandles[$this->logFile], $message . LF)) {
			throw new \RuntimeException('Could not write log record to log file', 1345036335);
		}

		return $this;
	}
}
