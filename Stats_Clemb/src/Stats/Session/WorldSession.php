<?php

namespace Stats\Session;

class WorldSession
{

    private $protection = false;

    /**
     * WorldSession constructor.
     * @param bool $protection
     */
    public function __construct(bool $protection)
    {
        $this->protection = $protection;
    }

    /**
     * @return bool
     */
    public function isProtected(): bool
    {
        return $this->protection;
    }

    /**
     * @param bool $protection
     */
    public function setProtection(bool $protection): void
    {
        $this->protection = $protection;
    }


}