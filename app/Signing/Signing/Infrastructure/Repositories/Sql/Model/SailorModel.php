<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use Illuminate\Database\Eloquent\Model;

class SailorModel extends Model
{
    protected $table = 'sailor';

    public function rentalPackages()
    {
        return $this->hasMany(SailorRentalPackageModel::class, 'sailor_id', 'id');
    }
}
