<?php

namespace App\Utils;

class DecodedJWT
{
    protected $decoded = null;

    public function set($data)
    {
        $this->decoded = $data;
    }

    public function get($node = null)
    {
        if ($node === null) {
            return $this->decoded;
        }

        if (isset($this->decoded) && isset($this->decoded->{$node})) {
            return $this->decoded->{$node};
        }

        return null;
    }
}
