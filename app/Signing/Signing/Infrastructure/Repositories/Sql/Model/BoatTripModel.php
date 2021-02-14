<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoatTripModel extends Model
{
    protected $table = 'boat_trip';

    protected $fillable = ['uuid', 'number_boats', 'name', 'number_hours', 'start_at', 'end_at'];

    public function toDomain():BoatTrip
    {
        $boatTripDuration = new BoatTripDuration($this->start_at, $this->number_hours, $this->end_at);
        return new BoatTrip(new Id($this->uuid), $boatTripDuration, $this->support_id, $this->number_boats, $this->name, $this->member_id);
    }

    public function support():BelongsTo
    {
        return $this->belongsTo(SupportModel::class);
    }
}
