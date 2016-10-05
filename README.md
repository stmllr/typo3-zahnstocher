# Zahnstocher

Zahnstocher (en. toothpick) is a TYPO3 extension which helps you piercing into TYPO3 code.

## Features

### TYPO3 6.x: Enhanced IntrospectionProcessor for the Logging API

This processor allows to add a stacktrace from where the log record comes from.
It was backported from TYPO3 7.x

```
$GLOBALS['TYPO3_CONF_VARS']['LOG']['processorConfiguration'] = [
		\TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
			\Stmllr\Zahnstocher\Log\Processor\IntrospectionProcessor::class => [
				'appendFullBackTrace' => TRUE,
				'shiftBackTraceLevel' => 4,
			],
		],
	],
];
```

### FileWriter which uses print_r instead of json to format its data.

Provides a better readability when watching the log on the command line.

```
$GLOBALS['TYPO3_CONF_VARS']['LOG']['writerConfiguration'] = [
		\TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
			\Stmllr\Zahnstocher\Log\\Writer\FileWriter::class => [
				'logFile' => 'typo3temp/logs/typo3.log'
			],
		],
	],
];
```
