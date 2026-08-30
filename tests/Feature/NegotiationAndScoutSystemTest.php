<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\ScoutAgent;
use App\Models\Team;
use App\Models\TransferListing;
use App\Models\TransferOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NegotiationAndScoutSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_register_scouting_agent()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.scouts.store'), [
            'name' => 'Kofi Annan',
            'experience_rating' => 5,
            'specialization' => 'Attacking Talent',
            'weekly_fee' => 1200.00,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('scout_agents', [
            'name' => 'Kofi Annan',
            'specialization' => 'Attacking Talent',
        ]);
    }

    public function test_manager_can_sign_and_release_scouting_agent()
    {
        $team = Team::create(['team_name' => 'Kumasi Stars', 'reference_code' => 'KUM-001', 'team_size' => 22, 'division' => 'Premier', 'primary_color' => '#ff0000', 'secondary_color' => '#ffffff', 'accent_color' => '#00ff00', 'password' => 'password']);
        $manager = User::factory()->create(['role' => 'manager', 'team_id' => $team->id]);
        $agent = ScoutAgent::create([
            'name' => 'Yaw Boahen',
            'experience_rating' => 4,
            'specialization' => 'Youth',
            'weekly_fee' => 800,
        ]);

        // Sign scout agent
        $response = $this->actingAs($manager)->post(route('manager.scouts.sign', $agent->id));
        $response->assertRedirect();
        $this->assertTrue($team->scoutAgents()->where('scout_agent_id', $agent->id)->exists());

        // Release scout agent
        $response = $this->actingAs($manager)->post(route('manager.scouts.release', $agent->id));
        $response->assertRedirect();
        $this->assertFalse($team->scoutAgents()->where('scout_agent_id', $agent->id)->exists());
    }

    public function test_manager_can_submit_scouted_player_to_squad_or_market()
    {
        $team = Team::create(['team_name' => 'Accra Lions', 'reference_code' => 'ACC-001', 'team_size' => 22, 'division' => 'Premier', 'primary_color' => '#ff0000', 'secondary_color' => '#ffffff', 'accent_color' => '#00ff00', 'password' => 'password']);
        $manager = User::factory()->create(['role' => 'manager', 'team_id' => $team->id]);

        // Submit player directly to squad
        $response = $this->actingAs($manager)->post(route('manager.scouts.submit-player'), [
            'name' => 'Kweku Prospect',
            'position' => 'FWD',
            'rating' => 82,
            'age' => 19,
            'nationality' => 'Ghanaian',
            'destination' => 'squad',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('players', [
            'name' => 'Kweku Prospect',
            'team_id' => $team->id,
            'rating' => 82,
        ]);

        // Submit player directly to market on loan
        $response = $this->actingAs($manager)->post(route('manager.scouts.submit-player'), [
            'name' => 'Emmanuel Prodigy',
            'position' => 'MID',
            'rating' => 77,
            'age' => 20,
            'nationality' => 'Ghanaian',
            'destination' => 'market',
            'asking_price' => 500000,
            'listing_type' => 'loan_half',
        ]);

        $response->assertRedirect();
        $player = Player::where('name', 'Emmanuel Prodigy')->first();
        $this->assertNotNull($player);
        $this->assertDatabaseHas('transfer_listings', [
            'player_id' => $player->id,
            'type' => 'loan_half',
            'asking_price' => 500000,
        ]);
    }

    public function test_manager_can_list_player_and_receive_bid_with_counter_offer()
    {
        $teamA = Team::create(['team_name' => 'Cape Coast FC', 'reference_code' => 'CPC-001', 'team_size' => 22, 'division' => 'Premier', 'primary_color' => '#ff0000', 'secondary_color' => '#ffffff', 'accent_color' => '#00ff00', 'password' => 'password']);
        $teamB = Team::create(['team_name' => 'Tamale City', 'reference_code' => 'TML-001', 'team_size' => 22, 'division' => 'Premier', 'primary_color' => '#0000ff', 'secondary_color' => '#ffffff', 'accent_color' => '#ffff00', 'password' => 'password']);

        $managerA = User::factory()->create(['role' => 'manager', 'team_id' => $teamA->id]);
        $managerB = User::factory()->create(['role' => 'manager', 'team_id' => $teamB->id]);

        $player = Player::create([
            'team_id' => $teamA->id,
            'name' => 'Samuel Striker',
            'position' => 'FWD',
            'rating' => 85,
            'goals' => 10,
        ]);

        // Manager A lists player for full season loan
        $this->actingAs($managerA)->post(route('manager.transfers.list'), [
            'player_id' => $player->id,
            'asking_price' => 750000,
            'type' => 'loan_full',
            'reason' => 'Development loan',
        ]);

        $listing = TransferListing::where('player_id', $player->id)->first();

        // Manager B submits bid for loan
        $this->actingAs($managerB)->post(route('manager.transfers.offer'), [
            'player_id' => $player->id,
            'listing_id' => $listing->id,
            'offer_amount' => 600000,
            'offer_type' => 'loan_full',
            'notes' => 'Will cover full salary',
        ]);

        $offer = TransferOffer::where('player_id', $player->id)->first();
        $this->assertNotNull($offer);

        // Manager A sends counter offer
        $response = $this->actingAs($managerA)->post(route('manager.transfers.handle', $offer->id), [
            'action' => 'counter',
            'counter_amount' => 700000,
            'counter_notes' => 'Minimum fee acceptable is 700k',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transfer_offers', [
            'id' => $offer->id,
            'status' => 'countered',
            'counter_amount' => 700000,
        ]);

        // Manager A accepts the offer ultimately
        $this->actingAs($managerA)->post(route('manager.transfers.handle', $offer->id), [
            'action' => 'accept',
        ]);

        $player->refresh();
        $this->assertEquals($teamB->id, $player->team_id);
        $this->assertDatabaseHas('articles', [
            'tag' => 'Transfer',
        ]);
    }
}
