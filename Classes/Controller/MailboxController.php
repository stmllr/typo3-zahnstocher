<?php
namespace Stmllr\Zahnstocher\Controller;

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

use Stmllr\Zahnstocher\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Mailbox Controller
 */
class MailboxController {

	/**
	 * @var string
	 */
	protected $template = '<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
	%s
	</body>
</html>';

	/**
	 * @var string
	 */
	protected $mailboxPath = '';

	/**
	 * @var \Stmllr\Zahnstocher\Configuration\ExtensionConfiguration
	 */
	protected $extensionConfiguration = NULL;


	/**
	 * Constructor
	 */
	public function __construct(array $container) {
	    /** @var ExtensionConfiguration extensionConfiguration */
		$this->extensionConfiguration = $container['extensionConfiguration'];
		$this->mailboxPath = $this->extensionConfiguration->getMailboxPath();
	}

	/**
	 * show mailbox
	 *
	 * @return bool
	 */
	public function showAction() {
	    if (!file_exists($this->mailboxPath)) {
            GeneralUtility::writeFile($this->mailboxPath, '', true);
            $fileContent = '';
        } else {
            $fileContent = file_get_contents($this->mailboxPath);
        }
		$fileContent = (empty($fileContent)) ? 'Mailbox is empty' : $fileContent;
		$content = sprintf($this->template, quoted_printable_decode($fileContent));

		echo $content;
		flush();
		exit();
	}

	/**
	 * flush mailbox
	 *
	 * @return bool
	 */
	public function flushAction() {
		$result = $this->removeMailboxFile();
		if ($result === TRUE) {
			$content = 'The mailbox file was successfully flushed';
		} else {
			$content = 'Failed to flush the mailbox file.';
		}

		$content = sprintf($this->template, $content);

		echo $content;
		flush();
		exit();
	}

	/**
	 * Remove mailbox file
	 *
	 * @return bool
	 */
	protected function removeMailboxFile() {
		$result = FALSE;

		if (file_exists($this->mailboxPath)) {
			@unlink($this->mailboxPath);
			$result = TRUE;
		}
		GeneralUtility::writeFile($this->mailboxPath, '', true);

		return $result;
	}
}
