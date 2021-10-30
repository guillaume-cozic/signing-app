<?php

namespace App\Signing\Shared\Entities;

abstract class Entity
{
    private ?int $surrogateId = null;

    public function setSurrogateId(int $id)
    {
        $this->surrogateId = $id;
    }

    public function surrogateId():?int
    {
        return $this->surrogateId;
    }
}
