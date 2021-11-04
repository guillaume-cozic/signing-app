<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Signing\Domain\Entities\State\SailorState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SailorModel extends Model
{
    use ScopeSailingClub;

    protected $table = 'sailor';

    public function rentalPackages(): HasMany
    {
        return $this->hasMany(SailorRentalPackageModel::class, 'sailor_id', 'id');
    }

    public function toState():SailorState
    {
        return new SailorState($this->name, null, null, null, $this->uuid);
    }
}
