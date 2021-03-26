<?php


namespace Tests\Adapters;


use App\Signing\Shared\Entities\Context;
use App\Signing\Shared\Services\ContextService;

class ContextServiceTestImpl implements ContextService
{
    private Context $context;
    private int $sailingClubId;

    public function get(): Context
    {
        if(!isset($this->context)){
            $this->set();
        }
        return $this->context;
    }

    public function set()
    {
        $this->context = new Context($this->sailingClubId);
    }

    public function setSailingClubId(int $sailingClubId)
    {
        $this->sailingClubId = $sailingClubId;
    }

}
