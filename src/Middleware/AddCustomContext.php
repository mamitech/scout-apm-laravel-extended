<?php

declare(strict_types=1);

namespace Mamitech\ScoutApmLaravelExtended\Middleware;

use Closure;
use Illuminate\Http\Request;
use Scoutapm\Logger\FilteredLogLevelDecorator;
use Scoutapm\ScoutApmAgent;
use Throwable;

final class AddCustomContext
{
    /** @var ScoutApmAgent */
    private $agent;

    /** @var FilteredLogLevelDecorator */
    private $logger;

    public function __construct(ScoutApmAgent $agent, FilteredLogLevelDecorator $logger)
    {
        $this->agent = $agent;
        $this->logger = $logger;
    }

    /**
     * @psalm-param Closure(Request):mixed $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $addParamsEnabled = config('scout-apm-laravel-extended.custom_context_enabled', false);
            if ($addParamsEnabled) {
                $this->agent->addContext('request_body', json_encode($request->input()));
                foreach ($request->input() as $key => $value) {
                    $this->agent->addContext('params.'.$key, json_encode($value));
                }
            }
        } catch (Throwable $e) {
            $this->logger->debug('SendRequestToScout failed: '.$e->getMessage(), ['exception' => $e]);
        }

        return $next($request);
    }
}
