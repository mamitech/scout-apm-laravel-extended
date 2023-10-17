<?php

namespace Mamitech\ScoutApmLaravelExtended;

class PHPHelper
{
    public function rand(int $min, int $max): int
    {
        return rand($min, $max);
    }
}
