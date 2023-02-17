<?php

namespace Stats\Session;

use Stats\Status;

class PlayerSession
{

    private $username;
    private $hp;
    private $force;
    private $shield;
    private $aditional;//chakra
    private $pvp = [false, 0];

    private $lastEvent = [
        "hp" => 0,
        "force" => 0,
        "shield" => 0,
        "aditional" => 0
    ];

    public function __construct(string $username)
    {
        $this->username = $username;
        $this->hp = new Status(10);
        $this->force = new Status(10);
        $this->shield = new Status(5);
        $this->aditional = new Status(0);
    }


    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return Status
     */
    public function getHp(): Status
    {
        return $this->hp;
    }

    /**
     * @return Status
     */
    public function getForce(): Status
    {
        return $this->force;
    }

    /**
     * @return Status
     */
    public function getShield(): Status
    {
        return $this->shield;
    }

    /**
     * @return Status
     */
    public function getAditional(): Status
    {
        return $this->aditional;
    }

    /**
     * @param string $status
     * @return float
     */
    public function getLastEvent(string $status): float
    {
        return $this->lastEvent[$status];
    }

    /**
     * @return array
     */
    public function getLastEvents(): array
    {
        return $this->lastEvent;
    }

    /**
     * @param string $status
     * @param float $lastEvent
     */
    public function setLastEvent(string $status, float $lastEvent): void
    {
        $this->lastEvent[$status] = $lastEvent;
    }

    /**
     * @param array $events
     */
    public function setLastEvents(array $events): void
    {
        $this->lastEvent = $events;
    }

    /**
     * @return array
     */
    public function getPvp(): array
    {
        return $this->pvp;
    }

    /**
     * @param array $pvp
     */
    public function setPvp(array $pvp): void
    {
        $this->pvp = $pvp;
    }




}