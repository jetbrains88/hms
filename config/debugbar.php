<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Debugbar Settings
     |--------------------------------------------------------------------------
     |
     | Debugbar is enabled by default, when debug is set to true in app.php.
     | You can override the value by setting enable to true or false instead of null.
     |
     | You can provide an array of URI's that must be ignored (eg. 'api/*')
     |
     */

//    'enabled' => env('DEBUGBAR_ENABLED', env('APP_DEBUG', false)),
    'enabled' => false,
    'except' => [
        'telescope*',
        'horizon*',
        'vendor/telescope*',
        'vendor/horizon*',
    ],

    /*
     |--------------------------------------------------------------------------
     | Storage settings
     |--------------------------------------------------------------------------
     |
     | DebugBar stores data for session/ajax requests.
     | You can disable this, so the debugbar stores data in headers/session,
     | but this can cause problems with large data collectors.
     | By default, file storage (in the storage folder) is used. Redis and PDO
     | can also be used. For PDO, run the package migrations first.
     |
     | Warning: Enabling storage.open will allow everyone to access previous
     | request, do not enable open storage in publicly available environments!
     | Specify a callback if you want to limit based on IP or authentication.
     |
     */
    'storage' => [
        'enabled' => env('DEBUGBAR_STORAGE_ENABLED', false),
        'driver' => 'file', // redis, file, pdo, socket, custom
        'path' => storage_path('debugbar'), // For file driver
        'connection' => null,   // Leave null for default connection (Redis/PDO)
        'provider' => '', // Instance of StorageInterface for custom driver
        'hostname' => '127.0.0.1', // Hostname to use with the "socket" driver
        'port' => 2304, // Port to use with the "socket" driver
        'capture_ajax' => true,
        'capture_console' => false,
        // Always use pre-scripts (default added to views before closing </body>);
        // Set to false for runtime added
        'always_inject' => true,
        // Optional: limit number of collected requests
        'max_history' => 50,
        'open' => env('DEBUGBAR_STORAGE_OPEN', false), // bool/callback
    ],

    /*
    |--------------------------------------------------------------------------
    | Vendors
    |--------------------------------------------------------------------------
    |
    | Vendor files are included by default, but can be set to false.
    | This can also be set to 'js' or 'css', to only include javascript or css vendor files.
    | Vendor files are for css: font-awesome (including fonts) and highlight.js (css files)
    | and for js: jquery and highlight.js
    | So if you want syntax highlighting, set it to true.
    | jQuery is set to not conflict with existing jQuery scripts.
    |
    */
    'include_vendors' => true,

    /*
    |--------------------------------------------------------------------------
    | Capture Ajax Requests
    |--------------------------------------------------------------------------
    |
    | The Debugbar can capture Ajax requests and display them. If you don't want this (ie. because of errors),
    | you can use this option to disable sending the data through the headers.
    |
    | Optionally, you can also send ServerTiming headers on ajax requests for the Chrome DevTools.
    |
    | Note for your request to be identified as ajax requests they must either send the header
    | X-Requested-With with the value XMLHttpRequest (most JS libraries send this), or have application/json as a Accept header.
    |
    */
    'capture_ajax' => true,
    'add_ajax_timing' => false,

    /*
    |--------------------------------------------------------------------------
    | Custom Error Handler for Deprecated warnings
    |--------------------------------------------------------------------------
    |
    | When enabled, the Debugbar shows deprecated warnings for Symfony components
    | in the Messages tab.
    |
    */
    'error_handler' => false,

    /*
    |--------------------------------------------------------------------------
    | Clockwork integration
    |--------------------------------------------------------------------------
    |
    | The Debugbar can emulate the Clockwork headers, so you can use the Chrome
    | Extension, without the server-side code. It uses Debugbar collectors instead.
    |
    */
    'clockwork' => false,

    /*
    |--------------------------------------------------------------------------
    | DataCollectors
    |--------------------------------------------------------------------------
    |
    | Enable/disable DataCollectors
    |
    */
    'collectors' => [
        'phpinfo' => true,  // Php version
        'messages' => true,  // Messages
        'time' => true,  // Time Datalogger
        'memory' => true,  // Memory usage
        'exceptions' => true,  // Exception displayer
        'log' => true,  // Logs from Monolog (merged in messages if enabled)
        'db' => true,  // Show database (PDO) queries and bindings
        'views' => true,  // Views with their data
        'route' => true,  // Current route information
        'auth' => false, // Display Laravel authentication status
        'gate' => true,  // Display Laravel Gate checks
        'session' => true,  // Display session data
        'symfony_request' => true,  // Only one can be enabled..
        'mail' => true,  // Catch mail messages
        'laravel' => false, // Laravel version and environment
        'events' => false, // All events fired
        'default_request' => false, // Regular or special Symfony request logger
        'logs' => false, // Add the latest log messages
        'files' => false, // Show the included files
        'config' => false, // Display config settings
        'cache' => false, // Display cache events
        'models' => true,  // Display models
        'livewire' => true,  // Display Livewire (when available)
    ],

    /*
    |--------------------------------------------------------------------------
    | Extra options
    |--------------------------------------------------------------------------
    |
    | Configure some DataCollectors
    |
    */
    'options' => [
        'time' => [
            'memory_usage' => false,  // Calculate memory usage between timers
        ],
        'messages' => [
            'trace' => true,  // Include stack traces for log messages
        ],
        'memory' => [
            'reset_peak' => false, // Reset peak memory usage after collecting
            'with_baseline' => false, // Show memory usage relative to baseline
            'baseline' => 0, // Baseline memory usage in bytes
        ],
        'auth' => [
            'show_name' => true,   // Also show the users name/email in the debugbar
        ],
        'db' => [
            'with_params' => true,   // Render SQL with parameters
            'backtrace' => true,   // Use a backtrace to find the origin of the query in your files.
            'backtrace_exclude_paths' => [],   // Paths to exclude from backtrace. (in addition to defaults)
            'timeline' => false,  // Add the queries to the timeline
            'duration_background' => true,   // Show shaded background on each query relative to how long it took to execute.
            'explain' => [                 // Show EXPLAIN output on queries
                'enabled' => false,
                'types' => ['SELECT'],     // Deprecated setting, is always only SELECT
            ],
            'hints' => true,    // Show hints for common mistakes
            'show_copy' => true,    // Show copy button next to the query
            'slow_threshold' => false,   // Only track queries that last longer than this time in ms
            'memory_usage' => false,   // Show queries memory usage
            'soft_limit' => 100,     // Soft limit of number of queries (does not show warning)
            'hard_limit' => 500,     // Hard limit of number of queries (logs warning)
        ],
        'mail' => [
            'full_log' => false,
            'show_body' => true,
        ],
        'views' => [
            'timeline' => false,    // Add the views to the timeline (Experimental)
            'data' => false,        // Note: Can slow down the application, because the data can be quite large..
            'group' => 50,          // Group duplicate views. Value is the threshold, set to 1 to group all duplicates
            'exclude_paths' => [    // Paths to exclude from view collector
                'vendor/filament'   // Exclude Filament components by default
            ],
        ],
        'route' => [
            'label' => true,  // Show complete route on bar
        ],
        'session' => [
            'hiddens' => [], // Sensitive session keys to hide from debugbar
        ],
        'symfony_request' => [
            'inject' => true,
            'hiddens' => [], // Hide request details (e.g. _token, password)
        ],
        'events' => [
            'data' => false, // Collect events data (can produce a lot of data)
        ],
        'logs' => [
            'file' => null, // Use a custom log file instead of storage_path('logs/laravel.log')
        ],
        'cache' => [
            'values' => true, // Collect cache values (can be expensive)
        ],
        'models' => [
            'include' => [], // Include specific models
            'exclude' => [], // Exclude specific models
            'events' => false, // Include model events (created, updated, etc.)
            'count' => false, // Show model counts
            'count_total' => false, // Show total model counts
            'hints' => true, // Show model hints (N+1, etc.)
            'show_events' => true, // Show model events in timeline
            'hide_abstract' => true, // Hide abstract models
            'soft_limit' => 100, // Soft limit of number of models (does not show warning)
            'hard_limit' => 500, // Hard limit of number of models (logs warning)
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Inject Debugbar in Response
    |--------------------------------------------------------------------------
    |
    | Usually, the debugbar is added just before </body>, by listening to the
    | Response after the App is done. If you disable this, you have to add them
    | in your template yourself. See http://phpdebugbar.com/docs/rendering.html
    |
    */
    'inject' => true,

    /*
    |--------------------------------------------------------------------------
    | DebugBar route prefix
    |--------------------------------------------------------------------------
    |
    | Sometimes you want to set route prefix to be used by debugbar to load
    | its resources from. Usually the need comes from misconfigured web server or
    | from trying to overcome bugs like this: http://trac.nginx.org/nginx/ticket/97
    |
    */
    'route_prefix' => '_debugbar',

    /*
    |--------------------------------------------------------------------------
    | DebugBar route domain
    |--------------------------------------------------------------------------
    |
    | By default DebugBar route served from the same domain that request served.
    | To override default domain, specify it as a non-empty value.
    */
    'route_domain' => null,

    /*
    |--------------------------------------------------------------------------
    | DebugBar theme
    |--------------------------------------------------------------------------
    |
    | Switches between light and dark theme. If auto is enabled, it will respect the
    | operating system theme. If set to null, it will use light theme.
    |
    */
    'theme' => env('DEBUGBAR_THEME', 'auto'),

    /*
    |--------------------------------------------------------------------------
    | Backtrace stack limit
    |--------------------------------------------------------------------------
    |
    | By default, the DebugBar limits the number of frames returned by the
    | backtrace to 50. You can increase or decrease this as needed.
    |
    */
    'backtrace_limit' => 50,

    /*
    |--------------------------------------------------------------------------
    | Backtrace excluded vendor paths
    |--------------------------------------------------------------------------
    |
    | DebugBar can optionally exclude vendor paths from the backtrace.
    | This is useful when you want to see the application code that triggered
    | the backtrace, rather than the vendor code.
    |
    */
    'backtrace_exclude_vendors' => true,

    /*
    |--------------------------------------------------------------------------
    | Backtrace in query bindings
    |--------------------------------------------------------------------------
    |
    | Show backtrace for query bindings. This can be useful to find where a
    | query binding value comes from.
    |
    */
    'backtrace_bindings' => false,

    /*
     |--------------------------------------------------------------------------
     | Backtrace excluded classes
     |--------------------------------------------------------------------------
     |
     | DebugBar can optionally exclude certain classes from the backtrace.
     | This is useful when you want to ignore certain classes in the backtrace.
     |
     */
    'backtrace_exclude_classes' => [],

    /*
     |--------------------------------------------------------------------------
     | Backtrace excluded namespace patterns
     |--------------------------------------------------------------------------
     |
     | DebugBar can optionally exclude certain namespace patterns from the backtrace.
     | This is useful when you want to ignore certain namespaces in the backtrace.
     |
     */
    'backtrace_exclude_namespaces' => [],

    /*
    |--------------------------------------------------------------------------
    | DebugBar date format
    |--------------------------------------------------------------------------
    |
    | Customize the date format used by the DebugBar.
    |
    */
    'date_format' => 'Y-m-d H:i:s',

    /*
    |--------------------------------------------------------------------------
    | DebugBar max string length
    |--------------------------------------------------------------------------
    |
    | Strings longer than this value will be truncated in the DebugBar.
    |
    */
    'max_string_length' => 1000,

    /*
    |--------------------------------------------------------------------------
    | Include stack traces in log messages
    |--------------------------------------------------------------------------
    |
    | If this is true, log messages will include stack traces.
    | If this is false, log messages will not include stack traces.
    |
    */
    'include_stacktraces' => true,

    /*
    |--------------------------------------------------------------------------
    | Include vendor files in stack traces
    |--------------------------------------------------------------------------
    |
    | If this is true, stack traces will include vendor files.
    | If this is false, stack traces will exclude vendor files.
    |
    */
    'include_vendors_in_stacktraces' => false,
];
