<?php

declare(strict_types=1);

namespace Mamitech\ScoutApmLaravelExtended\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mamitech\ScoutApmLaravelExtended\PHPHelper;
use Scoutapm\Logger\FilteredLogLevelDecorator;
use Scoutapm\ScoutApmAgent;
use Throwable;

final class SampleRequest
{
    /** @var ScoutApmAgent */
    private $agent;

    /** @var FilteredLogLevelDecorator */
    private $logger;

    /** @var PHPHelper */
    private $phpHelper;

    public function __construct(ScoutApmAgent $agent, FilteredLogLevelDecorator $logger, PHPHelper $phpHelper)
    {
        $this->agent = $agent;
        $this->logger = $logger;
        $this->phpHelper = $phpHelper;
    }

    /**
     * @psalm-param Closure(Request):mixed $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $samplingPer = (int) config('scout-apm-laravel-extended.sampling_per', 10);
            if ($this->phpHelper->rand(1, $samplingPer) === 1) {
                $this->agent->connect();
            } else {
                $this->agent->ignore();
                $this->logger->debug('SendRequestToScout skipped by sampling');
            }
        } catch (Throwable $e) {
            $this->logger->debug('SendRequestToScout failed: '.$e->getMessage(), ['exception' => $e]);
        }

        return $next($request);
    }
}
