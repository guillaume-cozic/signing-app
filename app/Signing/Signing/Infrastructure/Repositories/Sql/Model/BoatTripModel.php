<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatsCollection;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Dto\BoatTripsDTo;
use App\Signing\Signing\Domain\Entities\BoatTripDuration;
use App\Signing\Signing\Domain\Entities\Sailor;
use Illuminate\Database\Eloquent\Model;

class BoatTripModel extends Model
{
    protected $table = 'boat_trip';

    protected $fillable = ['number_hours', 'start_at', 'end_at'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'boats' => 'array'
    ];

    public function toDomain():BoatTrip
    {
        $boatTripDuration = new BoatTripDuration($this->start_at, $this->number_hours, $this->end_at);
        return new BoatTrip(
            new Id($this->uuid),
            $boatTripDuration,
            new Sailor($this->member_id, $this->name),
            new BoatsCollection($this->boats)
        );
    }

    public function setStartAtAttribute(?\DateTime $value)
    {
        $this->attributes['start_at'] = $value?->format('Y-m-d H:i:s.u');
    }

    public function setEndAtAttribute(?\DateTime $value)
    {
        $this->attributes['end_at'] = $value?->format('Y-m-d H:i:s.u');
    }

    public function toDto()
    {
        return new BoatTripsDTo(
            $this->uuid,
            $this->start_at,
            $this->end_at,
            $this->support?->name,
            $this->number_hours
        );
    }
}
