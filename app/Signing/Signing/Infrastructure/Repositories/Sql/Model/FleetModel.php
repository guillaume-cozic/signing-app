<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Dto\FleetDto;
use App\Signing\Signing\Domain\Entities\Fleet;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FleetModel extends Model
{
    use HasTranslations, ScopeSailingClub;

    protected $table = 'fleet';

    protected $fillable = ['uuid', 'total_available', 'sailing_club_id'];

    public $translatable = ['name'];

    public function toDomain():Fleet
    {
        return new Fleet(new Id($this->uuid), $this->total_available, $this->state);
    }

    public function toDto():FleetDto
    {
        return new FleetDto($this->uuid, $this->name, $this->total_available, $this->state);
    }
}
