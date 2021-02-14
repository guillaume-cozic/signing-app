<?php


namespace App\Signing\Shared\Providers;


interface DateProvider
{
    public function current():\DateTime;
}
