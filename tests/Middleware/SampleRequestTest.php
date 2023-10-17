<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mamitech\ScoutApmLaravelExtended\Middleware\SampleRequest;
use Mamitech\ScoutApmLaravelExtended\PHPHelper;
use Psr\Log\LoggerInterface;
use Scoutapm\Logger\FilteredLogLevelDecorator;
use Scoutapm\ScoutApmAgent;

uses(\phpmock\phpunit\PHPMock::class);

it('samples request when random hits', function () {
    config(['scout-apm-laravel-extended.sampling_per' => 1]);
    $mockAgent = Mockery::mock(ScoutApmAgent::class);
    $mockAgent->shouldReceive('connect');
    $mockLogger = Mockery::mock(LoggerInterface::class);
    $mockLogger->shouldNotReceive('log');
    $filteredLogLevel = new FilteredLogLevelDecorator(
        $mockLogger,
        'debug'
    );

    $expectedResponse = new Response();
    $sampleRequestMiddleware = new SampleRequest($mockAgent, $filteredLogLevel, new PHPHelper());
    $response = $sampleRequestMiddleware->handle(
        new Request(),
        static function () use ($expectedResponse) {
            return $expectedResponse;
        }
    );

    expect($expectedResponse === $response)->toBeTrue();
});

it('doesn\'t sample request when random doesn\'t hit', function () {
    $mockPHPHelper = Mockery::mock(PHPHelper::class);
    $mockPHPHelper->shouldReceive('rand')->andReturn(6);
    $mockAgent = Mockery::mock(ScoutApmAgent::class);
    $mockAgent->shouldNotReceive('connect');
    $mockLogger = Mockery::mock(LoggerInterface::class);
    $mockLogger->shouldReceive('log');
    $filteredLogLevel = new FilteredLogLevelDecorator(
        $mockLogger,
        'debug'
    );

    $expectedResponse = new Response();
    $sampleRequestMiddleware = new SampleRequest($mockAgent, $filteredLogLevel, $mockPHPHelper);
    $response = $sampleRequestMiddleware->handle(
        new Request(),
        static function () use ($expectedResponse) {
            return $expectedResponse;
        }
    );

    expect($expectedResponse === $response)->toBeTrue();
});
