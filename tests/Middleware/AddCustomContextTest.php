<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mamitech\ScoutApmLaravelExtended\Middleware\AddCustomContext;
use Psr\Log\LoggerInterface;
use Scoutapm\Logger\FilteredLogLevelDecorator;
use Scoutapm\ScoutApmAgent;

it('adds custom context when enabled', function () {
    putenv('SCOUT_CUSTOM_CONTEXT_ENABLED=true');

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
    $addCustomContextMiddleware = new AddCustomContext($mockAgent, $filteredLogLevel);
    $response = $addCustomContextMiddleware->handle(
        $request,
        static function () use ($expectedResponse) {
            return $expectedResponse;
        }
    );

    expect($expectedResponse === $response)->toBeTrue();
});

it('doesn\'t add custom context when disabled', function () {
    putenv('SCOUT_CUSTOM_CONTEXT_ENABLED=false');

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
    $addCustomContextMiddleware = new AddCustomContext($mockAgent, $filteredLogLevel);
    $response = $addCustomContextMiddleware->handle(
        $request,
        static function () use ($expectedResponse) {
            return $expectedResponse;
        }
    );

    expect($expectedResponse === $response)->toBeTrue();
});
