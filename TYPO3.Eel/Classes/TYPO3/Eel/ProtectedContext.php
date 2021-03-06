<?php
namespace TYPO3\Eel;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Eel".             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A protected evaluation context
 *
 * - Access to public properties and array is allowed
 * - Methods have to be whitelisted
 *
 * @Flow\Proxy(false)
 */
class ProtectedContext extends Context {

	/**
	 * @var array
	 */
	protected $whitelist = array();

	/**
	 * Call a method if in whitelist
	 *
	 * @param string $method
	 * @param array $arguments
	 * @return mixed|void
	 * @throws NotAllowedException
	 */
	public function call($method, array $arguments = array()) {
		if ($this->value === NULL || isset($this->whitelist[$method]) || isset($this->whitelist['*']) || ($this->value instanceof ProtectedContextAwareInterface && $this->value->allowsCallOfMethod($method))) {
			return parent::call($method, $arguments);
		}
		throw new NotAllowedException('Method "' . $method . '" is not callable in untrusted context', 1369043080);
	}

	/**
	 * Get a value by path and wrap it into another context
	 *
	 * The whitelist for the given path is applied to the new context.
	 *
	 * @param string $path
	 * @return \TYPO3\Eel\Context The wrapped value
	 */
	public function getAndWrap($path = NULL) {
		// There are some cases where the $path is a ProtectedContext, especially when doing s.th. like
		// foo()[myOffset]. In this case we need to unwrap it.
		if ($path instanceof ProtectedContext) {
			$path = $path->unwrap();
		}

		$context = parent::getAndWrap($path);
		if ($context instanceof ProtectedContext && isset($this->whitelist[$path]) && is_array($this->whitelist[$path])) {
			$context->whitelist = $this->whitelist[$path];
		}
		return $context;
	}

	/**
	 * Whitelist the given method (or array of methods) for calls
	 *
	 * Method can be whitelisted on the root level of the context or
	 * for arbitrary paths. A special method "*" will allow all methods
	 * to be called.
	 *
	 * Examples:
	 *
	 *   $context->whitelist('myMethod');
	 *
	 *   $context->whitelist('*');
	 *
	 *   $context->whitelist(array('String.*', 'Array.reverse'));
	 *
	 * @param array|string $pathOrMethods
	 * @return void
	 */
	public function whitelist($pathOrMethods) {
		if (!is_array($pathOrMethods)) {
			$pathOrMethods = array($pathOrMethods);
		}
		foreach ($pathOrMethods as $pathOrMethod) {
			$parts = explode('.', $pathOrMethod);
			$current = &$this->whitelist;
			$count = count($parts);
			for ($i = 0; $i < $count; $i++) {
				if ($i === $count - 1) {
					$current[$parts[$i]] = TRUE;
				} else {
					$current[$parts[$i]] = array();
					$current = &$current[$parts[$i]];
				}
			}
		}
	}

}
