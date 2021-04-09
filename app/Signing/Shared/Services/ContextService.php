<?php


namespace App\Signing\Shared\Services;


use App\Signing\Shared\Entities\Context;

interface ContextService
{
    public function get():Context;
    public function set(int $teamId = null);
}
