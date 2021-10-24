<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Shared\Services\ContextService;
use Illuminate\Database\Eloquent\Builder;

trait ScopeSailingClub
{
    public function scopeSailingClub($query):Builder
    {
        $sailingClubId = $this->contextService()->get()->sailingClubId();
        return $query->where($this->table.'.sailing_club_id', $sailingClubId);
    }

    public function contextService():ContextService
    {
        return app(ContextService::class);
    }
}
