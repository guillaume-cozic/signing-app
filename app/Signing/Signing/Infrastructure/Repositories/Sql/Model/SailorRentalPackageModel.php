<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use Illuminate\Database\Eloquent\Model;

class SailorRentalPackageModel extends Model
{
    protected $table = 'sailor_rental_package';

    protected $casts = ['end_validity' => 'date'];

    public function rentalPackage()
    {
        return $this->belongsTo(RentalPackageModel::class, 'rental_package_id', 'id');
    }
}
