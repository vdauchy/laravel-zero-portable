<?php

it('retrieves test version from the config', function () {
    expect($this->app->version())->toBe('Test version');
});

it('is running in the console', function () {
    expect($this->app->runningInConsole())->toBeTrue();
});
