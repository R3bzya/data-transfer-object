<?php

if (! function_exists('get_class_name')) {
    /**
     * @param object|string $class
     * @return string
     */
    function get_class_name($class): string
    {
        $className = is_object($class) ? get_class($class) : $class;

        if ($position = strrpos($className, '\\')) {
            return substr($className, $position + 1);
        }

        return $className;
    }
}

if (! function_exists('array_filter_keys')) {
    function array_filter_keys(array $array, ?callable $callable): array
    {
        return array_filter($array, $callable, ARRAY_FILTER_USE_KEY);
    }
}
