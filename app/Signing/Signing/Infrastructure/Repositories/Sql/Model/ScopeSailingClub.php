<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Shared\Services\ContextService;
use Illuminate\Database\Eloquent\Builder;

trait ScopeSailingClub
{
    public function scopeSailingClub($query):Builder
    {
        $sailingClubId = app(ContextService::class)->get()->sailingClubId();
        return $query->where('sailing_club_id', $sailingClubId);
    }
}
