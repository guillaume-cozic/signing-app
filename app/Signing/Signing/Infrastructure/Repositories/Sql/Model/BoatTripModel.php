<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\BoatTrip;
use App\Signing\Signing\Domain\Entities\Dto\BoatTripsDTo;
use App\Signing\Signing\Domain\Entities\Vo\BoatTripDuration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoatTripModel extends Model
{
    protected $table = 'boat_trip';

    protected $fillable = ['uuid', 'number_boats', 'name', 'number_hours', 'start_at', 'end_at'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime'
    ];

    public function toDomain():BoatTrip
    {
        $boatTripDuration = new BoatTripDuration($this->start_at, $this->number_hours, $this->end_at);
        return new BoatTrip(new Id($this->uuid), $boatTripDuration, $this->support_id, $this->number_boats, $this->name, $this->member_id);
    }

    public function support():BelongsTo
    {
        return $this->belongsTo(SupportModel::class);
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
