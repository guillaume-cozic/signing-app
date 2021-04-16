<?php


namespace App\Signing\Shared\Entities;


class User
{
    public function __construct(
        private string $id
    ){}

    public function id():string
    {
        return $this->id;
    }
}
