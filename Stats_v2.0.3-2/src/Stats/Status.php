<?php

namespace Stats;

class Status
{

    private $default;
    private $temporal;

    public function __construct(float $default)
    {
        $this->default = $default;
        $this->temporal = $default;
    }

    public function getDefault(): float
    {
        return $this->default;
    }

    public function setDefault(float $default): void
    {
        $this->default = $default;
    }

    public function getTemporal(): float
    {
        return $this->temporal;
    }

    public function setTemporal(float $temporal): void
    {
        $this->temporal = $temporal;
    }

}