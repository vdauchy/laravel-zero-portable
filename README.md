# laravel-zero-portable

Config-file-less wrapper around LaravelZero.
This let you create a CLI from a single file as:

```PHP
#!/usr/bin/env php
<?php

use VDauchy\LaravelZeroPortable\Commands\InspiringCommand;
use VDauchy\LaravelZeroPortable\LaravelZeroPortable;

$autoloader = require __DIR__ . '/vendor/autoload.php';

exit(LaravelZeroPortable::buildDefault(__DIR__, [
    InspiringCommand::class
])->run());
```