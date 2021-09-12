<?php

namespace Tests\Integration\Sql;

use App\Signing\Signing\Domain\Entities\State\SailorState;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SqlSailorRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function  shouldInsertSailor()
    {
        $sailor = new SailorState(name: 'guillaume', sailorId: 'sailor_id');
        $this->sailorRepository->save($sailor);

        $sailorSaved = $this->sailorRepository->getByName('guillaume');
        self::assertEquals($sailor, $sailorSaved->getState());
    }
}
