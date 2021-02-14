<?php


namespace Tests\Unit\Signing;


use App\Signing\Shared\Entities\Id;
use App\Signing\Signing\Domain\Entities\Support;
use App\Signing\Signing\Domain\UseCases\AddSupport;
use Tests\TestCase;

class AddSupportTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAddSupport()
    {
        $this->identityProvider->add($supportId = 'abc');
        app(AddSupport::class)->execute($title = 'hobie cat 15', $description = 'desc', $totalSupportAvailable = 20);

        $supportExpected = new Support(new Id($supportId), $totalSupportAvailable);
        $supportSaved = $this->supportRepository->get($supportId);
        self::assertEquals($supportExpected, $supportSaved);
    }

    /**
     * @test
     */
    public function shouldAddSupportTranslation()
    {
        $this->identityProvider->add($supportId = 'abc');
        app(AddSupport::class)->execute($title = 'hobie cat 15', $description = 'desc', $totalSupportAvailable = 20);

        $translationsSaved = $this->translationService->get('title', $supportId, $type = 'support');
        $translationExpected = ['fr' => $title];
        self::assertEquals($translationExpected, $translationsSaved);

        $translationsSaved = $this->translationService->get('description', $supportId, $type = 'support');
        $translationExpected = ['fr' => $description];
        self::assertEquals($translationExpected, $translationsSaved);
    }
}
