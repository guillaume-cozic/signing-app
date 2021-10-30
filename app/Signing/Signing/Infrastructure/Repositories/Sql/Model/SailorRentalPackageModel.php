<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use App\Signing\Signing\Domain\Entities\RentalPackage\ActionSailor;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackage;
use App\Signing\Signing\Domain\Entities\RentalPackage\SailorRentalPackageState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SailorRentalPackageModel extends Model
{
    protected $table = 'sailor_rental_package';

    protected $casts = ['end_validity' => 'date', 'actions' => 'array'];

    public function rentalPackage()
    {
        return $this->belongsTo(RentalPackageModel::class, 'rental_package_id', 'id');
    }

    public function sailor()
    {
        return $this->belongsTo(SailorModel::class, 'sailor_id', 'id');
    }

    public function toDomain():? SailorRentalPackage
    {
        if(isset($this->actions)) {
            foreach ($this->actions as $action) {
                $actions[] = new ActionSailor($action['type'], $action['hours'], (new Carbon())->setTimestamp(strtotime($action['at_time'])));
            }
        }
        return new SailorRentalPackage(
            $this->uuid,
            $this->sailor->uuid,
            $this->rentalPackage->uuid,
            $this->end_validity,
            $this->hours,
            $actions ?? []
        );
    }

    public function toState()
    {
        return new SailorRentalPackageState(
            $this->sailor_rental_id ?? $this->uuid,
            $this->sailor->name,
            $this->rentalPackage->uuid,
            $this->end_validity,
            $this->hours,
            $this->actions ?? []
        );
    }
}
