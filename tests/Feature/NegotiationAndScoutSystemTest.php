<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Player;
use App\Models\ScoutAgent;
use App\Models\Team;
use App\Models\TransferListing;
use App\Models\TransferOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NegotiationAndScoutSystemTest extends TestCase
{
    use RefreshDatabase;

    private function createTeam($name)
    {
        return Team::create([
            'reference_code' => 'APX-2025-' . rand(100, 999),
            'team_name' => $name,
            'team_size' => '25',
            'division' => 'premier',
            'primary_color' => '#ff0000',
            'secondary_color' => '#ffffff',
            'registration_status' => 'approved',
            'home_stadium' => $name . ' Stadium',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_admin_can_register_and_remove_scout_agents()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.scouts.store'), [
            'name' => 'Kofi Scout Master',
            'specialization' => 'Youth Prodigy Scout',
            'experience_rating' => 88,
            'weekly_fee' => 3000,
            'nationality' => '🇬🇭',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('scout_agents', [
            'name' => 'Kofi Scout Master',
            'specialization' => 'Youth Prodigy Scout',
            'experience_rating' => 88,
        ]);

        $scout = ScoutAgent::where('name', 'Kofi Scout Master')->first();

        $delResponse = $this->actingAs($admin)->delete(route('admin.scouts.destroy', $scout->id));
        $delResponse->assertRedirect();

        $this->assertDatabaseMissing('scout_agents', ['id' => $scout->id]);
    }

    public function test_manager_can_sign_and_release_scout_agent()
    {
        $team = $this->createTeam('Accra Lions');
        $manager = User::factory()->create(['role' => 'manager', 'team_id' => $team->id]);
        $scout = ScoutAgent::create([
            'name' => 'Kwame Scout',
            'specialization' => 'Tactical Specialist',
            'experience_rating' => 80,
            'weekly_fee' => 2000,
            'status' => 'available',
        ]);

        // Sign scout
        $signResp = $this->actingAs($manager)->post(route('manager.scouts.sign', $scout->id));
        $signResp->assertRedirect();
        $scout->refresh();
        $this->assertEquals('hired', $scout->status);
        $this->assertEquals($team->id, $scout->team_id);

        // Release scout
        $relResp = $this->actingAs($manager)->post(route('manager.scouts.release', $scout->id));
        $relResp->assertRedirect();
        $scout->refresh();
        $this->assertEquals('available', $scout->status);
        $this->assertNull($scout->team_id);
    }

    public function test_manager_can_submit_scouted_player_to_squad_and_market()
    {
        $team = $this->createTeam('Kumasi Warriors');
        $manager = User::factory()->create(['role' => 'manager', 'team_id' => $team->id]);

        // Submit to squad
        $squadResp = $this->actingAs($manager)->post(route('manager.scouts.submit-player'), [
            'name' => 'Abedi Pele Jr',
            'position' => 'MID',
            'rating' => 85,
            'nationality' => '🇬🇭',
            'destination' => 'squad',
        ]);
        $squadResp->assertRedirect();
        $this->assertDatabaseHas('players', [
            'name' => 'Abedi Pele Jr',
            'team_id' => $team->id,
            'rating' => 85,
        ]);

        // Submit directly to transfer market on half season loan
        $marketResp = $this->actingAs($manager)->post(route('manager.scouts.submit-player'), [
            'name' => 'Tony Yeboah Jr',
            'position' => 'FWD',
            'rating' => 88,
            'nationality' => '🇬🇭',
            'destination' => 'market',
            'asking_price' => 750000,
            'listing_type' => 'loan_half',
        ]);
        $marketResp->assertRedirect();

        $player = Player::where('name', 'Tony Yeboah Jr')->first();
        $this->assertNotNull($player);
        $this->assertDatabaseHas('transfer_listings', [
            'player_id' => $player->id,
            'team_id' => $team->id,
            'asking_price' => 750000,
            'type' => 'loan_half',
        ]);
    }

    public function test_permanent_and_loan_bidding_counter_offers_and_deal_execution()
    {
        $teamA = $this->createTeam('Cape Coast Stars');
        $teamB = $this->createTeam('Tema Titans');

        $managerA = User::factory()->create(['role' => 'manager', 'team_id' => $teamA->id]);
        $managerB = User::factory()->create(['role' => 'manager', 'team_id' => $teamB->id]);

        $player = Player::create([
            'team_id' => $teamA->id,
            'name' => 'Michael Essien',
            'position' => 'MID',
            'rating' => 90,
        ]);

        $listing = TransferListing::create([
            'player_id' => $player->id,
            'team_id' => $teamA->id,
            'asking_price' => 1000000,
            'type' => 'loan_full',
            'status' => 'active',
        ]);

        // Manager B bids for Full-Season Loan
        $bidResp = $this->actingAs($managerB)->post(route('manager.transfers.offer'), [
            'player_id' => $player->id,
            'offer_type' => 'loan_full',
            'offer_amount' => 800000,
            'notes' => 'Loan with option to buy next season.',
        ]);
        $bidResp->assertRedirect();

        $offer = TransferOffer::where('player_id', $player->id)->first();
        $this->assertEquals('pending', $offer->status);

        // Manager A counters the offer
        $counterResp = $this->actingAs($managerA)->post(route('manager.transfers.handle', $offer->id), [
            'action' => 'counter',
            'counter_amount' => 950000,
            'counter_notes' => 'We want GH₵ 950k minimum for full season loan.',
        ]);
        $counterResp->assertRedirect();

        $offer->refresh();
        $this->assertEquals('countered', $offer->status);
        $this->assertEquals(950000, $offer->counter_amount);

        // Manager B accepts counter-offer
        $acceptResp = $this->actingAs($managerB)->post(route('manager.transfers.handle', $offer->id), [
            'action' => 'accept',
        ]);
        $acceptResp->assertRedirect();

        $offer->refresh();
        $player->refresh();
        $listing->refresh();

        $this->assertEquals('accepted', $offer->status);
        $this->assertEquals($teamB->id, $player->team_id);
        $this->assertEquals('sold', $listing->status);

        $this->assertDatabaseHas('articles', [
            'tag' => 'Transfer',
        ]);
    }
}
