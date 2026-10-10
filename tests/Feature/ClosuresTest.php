<?php

namespace Tests\Feature;

use App\Support\Closures;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ClosuresTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['site.closures' => [
            ['dal' => '2026-08-10', 'al' => '2026-08-20', 'motivo' => ['it' => 'Ferie', 'en' => 'Holidays']],
        ]]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_an_upcoming_closure_is_announced_from_thirty_days_before(): void
    {
        Carbon::setTestNow('2026-07-10 12:00');
        $this->assertNull(Closures::notice());

        Carbon::setTestNow('2026-07-11 12:00');
        $this->assertSame('Ferie: chiusi dal 10 al 20 agosto.', Closures::notice());
    }

    public function test_an_ongoing_closure_says_when_the_restaurant_reopens(): void
    {
        Carbon::setTestNow('2026-08-15 20:00');

        $this->assertSame('Ferie: chiusi fino al 20 agosto, riapriamo il 21 agosto.', Closures::notice());
    }

    public function test_the_notice_disappears_after_the_last_closed_day(): void
    {
        Carbon::setTestNow('2026-08-20 23:00');
        $this->assertNotNull(Closures::notice());

        Carbon::setTestNow('2026-08-21 00:30');
        $this->assertNull(Closures::notice());
    }

    public function test_the_notice_is_translated_and_works_without_a_reason(): void
    {
        Carbon::setTestNow('2026-08-15 20:00');
        app()->setLocale('en');
        $this->assertSame('Holidays: closed until 20 August, we reopen on 21 August.', Closures::notice());

        app()->setLocale('it');
        config(['site.closures' => [['dal' => '2026-12-24', 'al' => '2026-12-26']]]);
        Carbon::setTestNow('2026-12-01 12:00');
        $this->assertSame('Chiusi dal 24 al 26 dicembre.', Closures::notice());
    }

    public function test_the_notice_is_shown_on_every_page_and_near_the_opening_hours(): void
    {
        Carbon::setTestNow('2026-08-15 20:00');
        $notice = 'Ferie: chiusi fino al 20 agosto, riapriamo il 21 agosto.';

        $this->get('/')->assertSeeText($notice);
        $this->assertSame(3, substr_count($this->get('/contatti')->getContent(), $notice), 'top bar, contact hours and footer hours');
    }

    public function test_nothing_is_shown_without_closures(): void
    {
        config(['site.closures' => []]);

        $this->get('/')->assertDontSee('role="status"', false);
    }
}
