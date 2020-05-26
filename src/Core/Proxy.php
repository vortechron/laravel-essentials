<?php

namespace Vortechron\Essentials\Core;

class Proxy
{
    protected $candidate;

    public function __construct($candidate)
    {
        $this->candidate = $candidate;
    }

    public function __get($key)
    {
        try {
            return $this->candidate->{$key};
        } catch (\Exception $e) {
            return $this;
        }
    }

    public function __call($name, $args)
    {
        try {
            return $this->candidate->{$name}(...$args);
        } catch (\Exception $e) {
            return $this;
        }
    }

    public function __toString()
    {
        return (string) null;
    }
}