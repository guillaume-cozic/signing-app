<?php


namespace App\Signing\Signing\Infrastructure\Repositories\Sql\Model;


use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SupportModel extends Model
{
    use HasTranslations;

    protected $table = 'support';

    protected $fillable = ['uuid', 'total_available'];

    public $translatable = ['name'];
}
