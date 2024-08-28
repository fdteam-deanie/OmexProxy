<?php

namespace App\ThirdApiClients\IpQualityScore;

class Response
{
    public ?int $fraudScore = null;

    public function __construct(?int $fraudScore)
    {
        $this->fraudScore = $fraudScore;
    }

    public function getFraudScore(): ?int
    {
        return $this->fraudScore;
    }
}
