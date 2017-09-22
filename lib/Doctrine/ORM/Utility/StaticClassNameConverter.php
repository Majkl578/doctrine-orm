<?php


declare(strict_types=1);

namespace Doctrine\ORM\Utility;

use ProxyManager\Configuration;
use ProxyManager\Inflector\ClassNameInflectorInterface;

/**
 * This class provides utility method to retrieve class names, and to convert
 * proxy class names to original class names
 *
 * @since  3.0
 * @author Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
abstract class StaticClassNameConverter
{
    /**
     * @var ClassNameInflectorInterface|null
     */
    private static $classNameInflector;

    final private function __construct()
    {
    }

    /**
     * Gets the real class name of a class name that could be a proxy.
     *
     * @param string $class
     *
     * @return string
     */
    public static function getRealClass($class)
    {
        $inflector = self::$classNameInflector
            ?? self::$classNameInflector = (new Configuration())->getClassNameInflector();

        return $inflector->getUserClassName($class);
    }

    /**
     * Gets the real class name of an object (even if its a proxy).
     *
     * @param object $object
     *
     * @return string
     */
    public static function getClass($object)
    {
        $inflector = self::$classNameInflector
            ?? self::$classNameInflector = (new Configuration())->getClassNameInflector();

        return $inflector->getUserClassName(\get_class($object));
    }
}
