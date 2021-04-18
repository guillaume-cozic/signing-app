<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Models\User;
use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatsCollection;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Dto\BoatTripsDTo;
use App\Signing\Signing\Domain\Entities\BoatTripDuration;
use App\Signing\Signing\Domain\Entities\Sailor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoatTripModel extends Model
{
    use ScopeSailingClub;

    protected $table = 'boat_trip';

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'boats' => 'array'
    ];

    public function member():BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }

    public function toDomain():BoatTrip
    {
        $boatTripDuration = new BoatTripDuration($this->should_start_at, $this->start_at, $this->number_hours, $this->end_at);
        return new BoatTrip(
            new Id($this->uuid),
            $boatTripDuration,
            new Sailor($this->member?->uuid, $this->name),
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

    public function totalBoats()
    {
        $total = 0;
        foreach($this->boats as $boatId => $qty){
            $total += $qty;
        }
        return $total;
    }

    public function toDto()
    {
        $boats = [];
        foreach($this->boats as $boatId => $qty){
            $boat = FleetModel::query()->where('uuid', $boatId)->first();
            $boats[$boat->name] = $qty;
        }
        return new BoatTripsDTo(
            $this->uuid,
            new Carbon($this->start_at),
            $this->end_at ? new Carbon($this->end_at) : null,
            $this->member !== null ? $this->member->firstname.' '.$this->member->surname : $this->name,
            $boats,
            $this->number_hours
        );
    }
}
