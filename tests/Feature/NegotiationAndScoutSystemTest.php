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
use Tests\TestCase;

class NegotiationAndScoutSystemTest extends TestCase
{
    use RefreshDatabase;

    private function createTeam($name, $code)
    {
        return Team::create([
            'team_name' => $name,
            'reference_code' => $code,
            'team_size' => 15,
            'division' => 'premier',
            'primary_color' => '#0000ff',
            'secondary_color' => '#ffffff',
            'password' => 'password',
            'registration_status' => 'approved',
        ]);
    }

    public function test_admin_can_register_and_delete_scouting_agent()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.scouts.index'));
        $response->assertStatus(200);
        $response->assertSee('Scouting Agent Registry');

        $postData = [
            'name' => 'Kofi Scout',
            'experience_rating' => 90,
            'specialization' => 'West African Talent',
            'weekly_fee' => 4000.00,
        ];

        $storeResponse = $this->actingAs($admin)->post(route('admin.scouts.store'), $postData);
        $storeResponse->assertRedirect();

        $agent = ScoutAgent::where('name', 'Kofi Scout')->first();
        $this->assertNotNull($agent);
        $this->assertEquals(90, $agent->experience_rating);

        $deleteResponse = $this->actingAs($admin)->delete(route('admin.scouts.destroy', $agent->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('scout_agents', ['id' => $agent->id]);
    }

    public function test_manager_can_sign_and_release_scout_agent()
    {
        $team = $this->createTeam('Accra City', 'ACC001');
        $manager = User::factory()->create(['role' => 'manager', 'team_id' => $team->id]);

        $scout = ScoutAgent::create([
            'name' => 'Agent Alex',
            'experience_rating' => 85,
            'specialization' => 'Free Agents',
            'weekly_fee' => 3000.00,
            'team_id' => null,
        ]);

        $indexResponse = $this->actingAs($manager)->get(route('manager.scouts.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Agent Alex');

        $signResponse = $this->actingAs($manager)->post(route('manager.scouts.sign', $scout->id));
        $signResponse->assertRedirect();
        $this->assertEquals($team->id, $scout->fresh()->team_id);

        $releaseResponse = $this->actingAs($manager)->post(route('manager.scouts.release', $scout->id));
        $releaseResponse->assertRedirect();
        $this->assertNull($scout->fresh()->team_id);
    }

    public function test_manager_with_scout_can_submit_new_player_to_squad_or_market()
    {
        $team = $this->createTeam('Cape Coast Stars', 'CCS002');
        $manager = User::factory()->create(['role' => 'manager', 'team_id' => $team->id]);

        ScoutAgent::create([
            'name' => 'Agent Maxwell',
            'experience_rating' => 88,
            'specialization' => 'Youth Prospects',
            'weekly_fee' => 3500.00,
            'team_id' => $team->id,
        ]);

        // Submit to squad
        $squadData = [
            'name' => 'Kwame Discover',
            'position' => 'FWD',
            'age' => 19,
            'rating' => 82,
            'estimated_value' => 200000,
            'submission_type' => 'squad',
        ];

        $res1 = $this->actingAs($manager)->post(route('manager.scouts.submit-player'), $squadData);
        $res1->assertRedirect();

        $player1 = Player::where('name', 'Kwame Discover')->first();
        $this->assertNotNull($player1);
        $this->assertEquals($team->id, $player1->team_id);

        // Submit to market
        $marketData = [
            'name' => 'Aboagye Scouted',
            'position' => 'MID',
            'age' => 21,
            'rating' => 80,
            'estimated_value' => 180000,
            'submission_type' => 'market',
        ];

        $res2 = $this->actingAs($manager)->post(route('manager.scouts.submit-player'), $marketData);
        $res2->assertRedirect();

        $player2 = Player::where('name', 'Aboagye Scouted')->first();
        $this->assertNotNull($player2);
        $listing = TransferListing::where('player_id', $player2->id)->first();
        $this->assertNotNull($listing);
        $this->assertEquals('active', $listing->status);
        $this->assertEquals(180000, $listing->asking_price);
    }

    public function test_full_negotiation_system_permanent_and_loan_bids_and_counter_offers()
    {
        $teamA = $this->createTeam('Accra Lions', 'ACL101');
        $managerA = User::factory()->create(['role' => 'manager', 'team_id' => $teamA->id]);

        $teamB = $this->createTeam('Kumasi Warriors', 'KMW102');
        $managerB = User::factory()->create(['role' => 'manager', 'team_id' => $teamB->id]);

        $player = Player::create([
            'team_id' => $teamB->id,
            'name' => 'Star Striker',
            'position' => 'FWD',
            'age' => 24,
            'rating' => 88,
        ]);

        $listing = TransferListing::create([
            'player_id' => $player->id,
            'team_id' => $teamB->id,
            'asking_price' => 500000,
            'type' => 'permanent',
            'status' => 'active',
        ]);

        // Manager A submits bid
        $bidData = [
            'player_id' => $player->id,
            'offer_amount' => 450000,
            'offer_type' => 'permanent',
            'notes' => 'Opening bid for Star Striker',
        ];

        $bidRes = $this->actingAs($managerA)->post(route('manager.transfers.offer'), $bidData);
        $bidRes->assertRedirect();

        $offer = TransferOffer::where('player_id', $player->id)->first();
        $this->assertNotNull($offer);
        $this->assertEquals('pending', $offer->status);

        // Manager B counters bid
        $counterData = [
            'action' => 'counter',
            'counter_amount' => 480000,
            'counter_type' => 'loan_full',
            'counter_notes' => 'We prefer a full-season loan with GH₵ 480k fee',
        ];

        $counterRes = $this->actingAs($managerB)->post(route('manager.transfers.handle', $offer->id), $counterData);
        $counterRes->assertRedirect();

        $offer->refresh();
        $this->assertEquals('countered', $offer->status);
        $this->assertEquals(480000, $offer->counter_amount);

        // Manager A accepts counter offer
        $acceptCounterRes = $this->actingAs($managerA)->post(route('manager.transfers.handle-counter', $offer->id), ['action' => 'accept']);
        $acceptCounterRes->assertRedirect();

        $offer->refresh();
        $player->refresh();
        $listing->refresh();

        $this->assertEquals('accepted', $offer->status);
        $this->assertEquals($teamA->id, $player->team_id);
        $this->assertEquals('sold', $listing->status);

        $article = Article::where('title', 'like', '%Star Striker%')->first();
        $this->assertNotNull($article);
        $this->assertTrue($article->is_published);
    }
}
