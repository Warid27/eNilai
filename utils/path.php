<?php

function resolvePath($path)
{
    static $aliases = null;
    if (!$aliases) {
        $aliases = [
            '@' => dirname(__DIR__),
            '@page' => dirname(__DIR__) . '/page',
            '@controller' => dirname(__DIR__) . '/controller',
            '@db' => dirname(__DIR__) . '/db',
            '@assets' => dirname(__DIR__) . '/assets',
            '@component' => dirname(__DIR__) . '/component',
            '@service' => dirname(__DIR__) . '/service',
            '@utils' => dirname(__DIR__) . '/utils',
        ];
    }
    foreach ($aliases as $alias => $real) {
        if (str_starts_with($path, $alias)) {
            return str_replace($alias, $real, $path);
        }
    }
    return $path;
}

