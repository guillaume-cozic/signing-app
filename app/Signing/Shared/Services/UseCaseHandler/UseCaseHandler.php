<?php

namespace App\Signing\Shared\Services\UseCaseHandler;

use App\Signing\Shared\Exception\DomainException;
use App\Signing\Shared\Exception\TechnicalException;
use Illuminate\Support\Facades\DB;

class UseCaseHandler
{
    public function __construct(private UseCase $useCase){}

    public function execute(Parameters $parameters)
    {
        $this->startTransaction();
        try{
            $result = $this->useCase->handle($parameters);
        } catch (DomainException $e){
            throw $e;
        } catch (\Throwable $e){
            $this->rollbackTransaction();
            report($e);
            throw $e;
        }
        $this->commitTransaction();
        return $result;
    }

    public function startTransaction()
    {
        DB::beginTransaction();
    }

    public function commitTransaction()
    {
        DB::commit();
    }

    public function rollbackTransaction()
    {
        DB::rollBack();
    }
}
