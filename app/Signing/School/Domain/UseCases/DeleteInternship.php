<?php

namespace App\Signing\School\Domain\UseCases;

interface DeleteInternship
{
    public function execute(string $id);
}
