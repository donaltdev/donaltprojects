<?php

namespace airdrops\sessions;

use pocketmine\utils\SingletonTrait;

class SessionFactory {
    use SingletonTrait;

    /** @var Session[] */
    private array $sessions = [];

    public function add(Session $session): void {
        $this->sessions[$session->getName()] = $session;
    }

    public function get(string $name): ?Session {
        return $this->sessions[$name] ?? null;
    }

    public function exist(string $name): bool {
        return isset($this->sessions[$name]);
    }

    public function delete(string $name): void {
        unset($this->sessions[$name]);
    }

    /**
     * @return Session[]
     */
    public function getSessions(): array {
        return $this->sessions;
    }
}