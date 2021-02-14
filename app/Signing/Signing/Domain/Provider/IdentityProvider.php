<?php


namespace App\Signing\Signing\Domain\Provider;


interface IdentityProvider
{
    public function get():string;
}
