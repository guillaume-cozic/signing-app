<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use Illuminate\Database\Eloquent\Model;

class SupportModel extends Model
{
    protected $table = 'support';

    protected $fillable = ['uuid', 'total_available'];


}
