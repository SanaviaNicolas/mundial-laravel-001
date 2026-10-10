<?php

namespace Tests\Feature;

use Tests\TestCase;

class CopyReviewTest extends TestCase
{
    public function test_home_does_not_promise_every_evening_while_closed_on_mondays(): void
    {
        $this->get('/')->assertDontSeeText('ogni sera')->assertDontSeeText('sa di tutto');
    }

    public function test_story_says_the_dough_is_felt_on_the_plate_but_not_afterwards(): void
    {
        $this->get('/la-nostra-storia')->assertSeeText('ma non dopo')->assertDontSeeText('e anche dopo');
    }

    public function test_contact_page_invites_to_call_for_bookings_and_information(): void
    {
        $this->get('/contatti')->assertSeeText('Per qualsiasi altra informazione');
    }

    public function test_privacy_policy_links_the_cookie_policy_and_the_garante(): void
    {
        $this->get('/privacy')
            ->assertSee('href="/cookie"', false)
            ->assertSee('href="https://www.garanteprivacy.it"', false);
        $this->get('/en/privacy')->assertSee('href="/en/cookies"', false);
    }

    public function test_booking_and_takeaway_are_by_phone_and_there_is_no_delivery(): void
    {
        foreach (['/', '/menu', '/contatti'] as $uri) {
            $this->get($uri)
                ->assertSeeText('Prenota un tavolo o ordina da asporto')
                ->assertSeeText('Non facciamo consegne a domicilio.');
        }

        $this->get('/en/contact')->assertSeeText('takeaway')->assertSeeText('We do not deliver.');
    }

    public function test_the_former_name_is_not_shown(): void
    {
        foreach (['/', '/en', '/menu'] as $uri) {
            $this->get($uri)->assertDontSee('Mundial 82');
        }
    }
}
