<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Signing\Domain\Entities\RentalPackage\RentalPackageState;
use Illuminate\Database\Eloquent\Model;

class RentalPackageModel extends Model
{
    protected $table = 'rental_package';

    protected $casts = ['fleets' => 'array'];

    public function toState():RentalPackageState
    {
        return new RentalPackageState($this->uuid, $this->fleets, $this->name, $this->validity);
    }
}
