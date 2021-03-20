<?php


namespace Tests\Unit\Signing\System;

use App\Signing\Signing\Domain\UseCases\System\CreateFleetWhenTeamCreated;
use Tests\TestCase;

class CreateFleetWhenTeamCreatedTest extends TestCase
{
    private CreateFleetWhenTeamCreated $createFleetWhenTeamCreated;

    public function setUp(): void
    {
        parent::setUp();
        $this->createFleetWhenTeamCreated = app(CreateFleetWhenTeamCreated::class);
    }

    /**
     * @test
     */
    public function shouldCreateDefaultFleetWhenTeamCreated()
    {
        $this->createFleetWhenTeamCreated->execute();
        $fleets = $this->fleetRepository->all();
        self::assertNotEmpty($fleets);
    }
}
