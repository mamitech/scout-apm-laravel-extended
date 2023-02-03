<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mamitech\ScoutApmLaravelExtended\Middleware\SampleRequest;
use Psr\Log\LoggerInterface;
use Scoutapm\Logger\FilteredLogLevelDecorator;
use Scoutapm\ScoutApmAgent;

uses(\phpmock\phpunit\PHPMock::class);

it('samples request when random hits', function () {
    $rand = $this->getFunctionMock("Mamitech\ScoutApmLaravelExtended\Middleware", "rand");
    $rand->expects($this->once())->willReturn(1);
    $mockAgent = Mockery::mock(ScoutApmAgent::class);
    $mockAgent->shouldReceive('connect');
    $mockLogger = Mockery::mock(LoggerInterface::class);
    $mockLogger->shouldNotReceive('log');
    $filteredLogLevel = new FilteredLogLevelDecorator(
        $mockLogger,
        'debug'
    );

    $expectedResponse = new Response();
    $sampleRequestMiddleware = new SampleRequest($mockAgent, $filteredLogLevel);
    $response = $sampleRequestMiddleware->handle(
        new Request(),
        static function () use ($expectedResponse) {
            return $expectedResponse;
        }
    );
    
    expect($expectedResponse === $response)->toBeTrue();
});

it('doesn\'t sample request when random doesn\'t hit', function () {
    $rand = $this->getFunctionMock("Mamitech\ScoutApmLaravelExtended\Middleware", "rand");
    $rand->expects($this->once())->willReturn(6);
    $mockAgent = Mockery::mock(ScoutApmAgent::class);
    $mockAgent->shouldNotReceive('connect');
    $mockLogger = Mockery::mock(LoggerInterface::class);
    $mockLogger->shouldReceive('log');
    $filteredLogLevel = new FilteredLogLevelDecorator(
        $mockLogger,
        'debug'
    );
    
    $expectedResponse = new Response();
    $sampleRequestMiddleware = new SampleRequest($mockAgent, $filteredLogLevel);
    $response = $sampleRequestMiddleware->handle(
        new Request(),
        static function () use ($expectedResponse) {
            return $expectedResponse;
        }
    );
    
    expect($expectedResponse === $response)->toBeTrue();
});