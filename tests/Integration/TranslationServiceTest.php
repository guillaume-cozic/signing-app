<?php


namespace Tests\Integration;


use App\Signing\Signing\Infrastructure\Repositories\Sql\Model\FleetModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\App;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class TranslationServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $id;

    public function setUp(): void
    {
        parent::setUp();
        (new FleetModel())->fill(
            [
                'uuid' => $this->id = Uuid::uuid4(),
                'total_available' => 1,
                'sailing_club_id' => 1
            ])->save();
    }

    /**
     * @test
     */
    public function shouldInsertTranslations()
    {
        $translations = [
            'name' => [
                    'fr' => 'translation fr',
                    'en' => 'translation en'
                ]
        ];

        $this->translationService->add($translations, $this->id, 'support');

        App::setLocale('fr');
        $translationsSaved = $this->translationService->get('name', $this->id, 'support');
        self::assertEquals($translations['name'][App::getLocale()], $translationsSaved);

        App::setLocale('en');
        $translationsSaved = $this->translationService->get('name', $this->id, 'support');
        self::assertEquals($translations['name'][App::getLocale()], $translationsSaved);
    }
}
