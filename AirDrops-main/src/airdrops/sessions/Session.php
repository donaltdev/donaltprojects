<?php

namespace airdrops\sessions;

class Session {

    private string $name;

    private array $antibuild = [
        "tag" => false,
        "used" => false,
        "cooldown" => 0,
        "expire" => 0
    ];

    private array $mushroom = [
        "used" => false,
        "cooldown" => 0,
        "expire" => 0,
        "tag" => false
    ];

    private array $zap = [
        "used" => false,
        "cooldown" => 0
    ];

    private bool $air = false;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function isAir(): bool {
        return $this->air;
    }

    public function setAir(bool $air): void {
        $this->air = $air;
    }

    public function getAntibuild(string $key): mixed {
        return $this->antibuild[$key];
    }

    public function getMushroom(string $key): mixed {
        return $this->mushroom[$key];
    }

    public function getZap(string $key): mixed {
        return $this->zap[$key];
    }

    public function setAntibuild(string $key, mixed $antibuild): void {
        $this->antibuild[$key] = $antibuild;
    }

    public function setMushroom(string $key, mixed $antibuild): void {
        $this->mushroom[$key] = $antibuild;
    }

    public function setZap(string $key, mixed $antibuild): void {
        $this->zap[$key] = $antibuild;
    }
}