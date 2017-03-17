<?php
namespace TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine;

/**
 * A Doctrine SqlFilter should implement this method if he makes use of setParameter; as setParameter is only allowed to be called
 * inside "initializeParameters", and NOT inside addFilterConstraint(). Otherwise, SQL query caching will not properly work, leading to
 * wrong permissions being applied.
 */
interface EarlyParameterInitializationSqlFilterInterface
{

    /**
     * Call "setParameter()" in this method.
     *
     * When this method is called, the security context can be initialized; so you can access the current user etc.
     *
     * @return void
     */
    public function initializeParameters();
}
