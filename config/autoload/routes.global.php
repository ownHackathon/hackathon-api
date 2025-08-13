<?php
return [
    'dependencies' => [
        //..
        'invokables' => [
            /* ... */
            // Comment out or remove the following line:
            // Mezzio\Router\RouterInterface::class => Mezzio\Router\FastRouteRouter::class,
            /* ... */
        ],
        'factories' => [
            /* ... */
            // Add this line; the specified factory now creates the router instance:
            /* ... */
        ],
    ],

    // Add the following to enable caching support:
    'router' => [
        'fastroute' => [
            // Enable caching support:
            'cache_enabled' => false,
            // Optional (but recommended) cache file path:
            'cache_file' => './../../data/cache/fastroute.php.cache',
        ],
    ],

    'routes' => [ /* ... */],
];
