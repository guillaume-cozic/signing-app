<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;
use Illuminate\Database\Eloquent\Model;

class SailorRentalPackageModel extends Model
{
    protected $table = 'sailor_rental_package';

    protected $casts = ['end_validity' => 'date'];

    public function rentalPackage()
    {
        return $this->belongsTo(RentalPackageModel::class, 'rental_package_id', 'id');
    }

    public function sailor()
    {
        return $this->belongsTo(SailorModel::class, 'sailor_id', 'id');
    }

    public function toState()
    {
        return new SailorRentalPackageState($this->uuid, $this->sailor->name, $this->rentalPackage->uuid, $this->end_validity, $this->hours);
    }
}
