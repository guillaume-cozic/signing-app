<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Signing\Domain\Entities\State\SailorState;
use Illuminate\Database\Eloquent\Model;

class SailorModel extends Model
{
    protected $table = 'sailor';

    public function rentalPackages()
    {
        return $this->hasMany(SailorRentalPackageModel::class, 'sailor_id', 'id');
    }

    public function toState():SailorState
    {
        return new SailorState($this->name, null, false, false, $this->sailor_id);
    }
}
