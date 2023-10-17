<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mamitech\ScoutApmLaravelExtended\Middleware\AddCustomContext;
use Mamitech\ScoutApmLaravelExtended\PHPHelper;
use Psr\Log\LoggerInterface;
use Scoutapm\Logger\FilteredLogLevelDecorator;
use Scoutapm\ScoutApmAgent;

it('adds custom context when enabled', function () {
    config(['scout-apm-laravel-extended.custom_context_enabled' => true]);

    $request = new Request();
    $postBody = [
        'offset' => 12,
    ];
    $request->replace($postBody);
    $mockAgent = Mockery::mock(ScoutApmAgent::class);
    $mockAgent->shouldReceive('addContext')->with('request_body', json_encode($request->input()));
    $mockAgent->shouldReceive('addContext')->with('params.offset', json_encode(12));

    $mockLogger = Mockery::mock(LoggerInterface::class);
    $mockLogger->shouldReceive('log');
    $filteredLogLevel = new FilteredLogLevelDecorator(
        $mockLogger,
        'debug'
    );

    $expectedResponse = new Response();
    $addCustomContextMiddleware = new AddCustomContext($mockAgent, $filteredLogLevel, new PHPHelper());
    $response = $addCustomContextMiddleware->handle(
        $request,
        static function () use ($expectedResponse) {
            return $expectedResponse;
        }
    );

    expect($expectedResponse === $response)->toBeTrue();
});

it('doesn\'t add custom context when disabled', function () {
    config(['scout-apm-laravel-extended.custom_context_enabled' => false]);

    $request = new Request();
    $postBody = [
        'offset' => 12,
    ];
    $request->replace($postBody);
    $mockAgent = Mockery::mock(ScoutApmAgent::class);
    $mockAgent->shouldNotReceive('addContext');

    $mockLogger = Mockery::mock(LoggerInterface::class);
    $mockLogger->shouldReceive('log');
    $filteredLogLevel = new FilteredLogLevelDecorator(
        $mockLogger,
        'debug'
    );

    $expectedResponse = new Response();
    $addCustomContextMiddleware = new AddCustomContext($mockAgent, $filteredLogLevel, new PHPHelper());
    $response = $addCustomContextMiddleware->handle(
        $request,
        static function () use ($expectedResponse) {
            return $expectedResponse;
        }
    );

    expect($expectedResponse === $response)->toBeTrue();
});
