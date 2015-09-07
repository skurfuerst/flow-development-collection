<?php

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Core\Booting\Scripts;

define('FLOW_PATH_FLOW', __DIR__ . '/../');
define('FLOW_PATH_ROOT', __DIR__ . '/../../../../');
define('FLOW_PATH_CONFIGURATION', FLOW_PATH_ROOT . '/Configuration');

require(__DIR__ . '/../Classes/TYPO3/Flow/Core/Booting/Scripts.php');
require(__DIR__ . '/../Classes/TYPO3/Flow/Utility/Algorithms.php');
array_shift($argv);
$commandIdentifier = array_shift($argv);
Scripts::executeCommandThroughWeb($commandIdentifier, $argv, getenv('FLOW_CONTEXT') ?: 'Development');