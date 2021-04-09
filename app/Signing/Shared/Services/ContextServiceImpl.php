<?php


namespace App\Signing\Shared\Services;


use App\Signing\Shared\Entities\Context;
use Illuminate\Support\Facades\Auth;

class ContextServiceImpl implements ContextService
{
    private Context $context;

    public function get(): Context
    {
        if(!isset($this->context)){
            $this->set();
        }
        return $this->context;
    }

    public function set(int $teamId = null)
    {
        $sailingClubId = Auth::user() !== null ? Auth::user()->currentTeam->id : $teamId;
        $this->context = new Context($sailingClubId);
    }

}
