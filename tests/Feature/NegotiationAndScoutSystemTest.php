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

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createTestTeam(array $attributes = []): Team
    {
        return Team::create(array_merge([
            'team_name' => 'Sample Club',
            'reference_code' => 'SMP-' . rand(100, 999),
            'team_size' => 22,
            'division' => 'Division 1',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'accent_color' => '#f0c040',
            'password' => 'secret123',
            'registration_status' => 'approved',
        ], $attributes));
    }

    public function test_admin_can_register_and_manage_scouting_agents()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.scouts.index'));
        $response->assertStatus(200);
        $response->assertSee('Scouting Agent Registry');

        $storeResponse = $this->actingAs($admin)->post(route('admin.scouts.store'), [
            'name' => 'Kofi Amoah',
            'specialization' => 'West Africa Youth Scout',
            'nationality' => 'Ghanaian',
            'experience_rating' => 88,
            'weekly_fee' => 750.00,
        ]);

        $storeResponse->assertRedirect();
        $this->assertDatabaseHas('scout_agents', [
            'name' => 'Kofi Amoah',
            'specialization' => 'West Africa Youth Scout',
            'status' => 'available',
        ]);
    }

    public function test_manager_can_sign_and_release_scouting_agent()
    {
        $team = $this->createTestTeam(['team_name' => 'Kumasi Warriors']);
        $manager = User::factory()->create(['role' => 'manager', 'team_id' => $team->id]);

        $scout = ScoutAgent::create([
            'name' => 'Samuel Owusu',
            'specialization' => 'Midfield Talent',
            'experience_rating' => 82,
            'weekly_fee' => 500.00,
            'status' => 'available',
        ]);

        // Manager signs scout
        $signResponse = $this->actingAs($manager)->post(route('manager.scouts.sign', $scout->id));
        $signResponse->assertRedirect();
        $this->assertDatabaseHas('scout_agents', [
            'id' => $scout->id,
            'team_id' => $team->id,
            'status' => 'hired',
        ]);

        // Manager releases scout
        $releaseResponse = $this->actingAs($manager)->post(route('manager.scouts.release', $scout->id));
        $releaseResponse->assertRedirect();
        $this->assertDatabaseHas('scout_agents', [
            'id' => $scout->id,
            'team_id' => null,
            'status' => 'available',
        ]);
    }

    public function test_scout_agent_can_create_and_submit_discovered_player()
    {
        $team = $this->createTestTeam(['team_name' => 'Accra Lions']);
        $manager = User::factory()->create(['role' => 'manager', 'team_id' => $team->id]);

        $scout = ScoutAgent::create([
            'name' => 'Daniel Addo',
            'specialization' => 'Striker Scout',
            'team_id' => $team->id,
            'status' => 'hired',
        ]);

        $submitResponse = $this->actingAs($manager)->post(route('manager.scouts.submit-player'), [
            'scout_agent_id' => $scout->id,
            'name' => 'Kwaku Wonder',
            'position' => 'FWD',
            'age' => 18,
            'nationality' => 'Ghanaian',
            'rating' => 84,
            'list_on_market' => 1,
            'deal_type' => 'loan_half',
            'asking_price' => 12000,
        ]);

        $submitResponse->assertRedirect();

        $this->assertDatabaseHas('players', [
            'name' => 'Kwaku Wonder',
            'team_id' => $team->id,
            'position' => 'FWD',
            'rating' => 84,
        ]);

        $player = Player::where('name', 'Kwaku Wonder')->first();

        $this->assertDatabaseHas('transfer_listings', [
            'player_id' => $player->id,
            'team_id' => $team->id,
            'deal_type' => 'loan_half',
            'scout_agent_id' => $scout->id,
            'asking_price' => 12000,
        ]);
    }

    public function test_full_negotiation_system_with_bids_loans_counter_offers_and_news()
    {
        $sellerTeam = $this->createTestTeam(['team_name' => 'Selling Stars']);
        $buyerTeam = $this->createTestTeam(['team_name' => 'Buying United']);

        $sellerManager = User::factory()->create(['role' => 'manager', 'team_id' => $sellerTeam->id]);
        $buyerManager = User::factory()->create(['role' => 'manager', 'team_id' => $buyerTeam->id]);

        $player = Player::create([
            'team_id' => $sellerTeam->id,
            'name' => 'Abena Kyei',
            'position' => 'MID',
            'age' => 22,
            'rating' => 80,
            'status' => 'active',
        ]);

        $listing = TransferListing::create([
            'player_id' => $player->id,
            'team_id' => $sellerTeam->id,
            'asking_price' => 25000,
            'type' => 'loan',
            'deal_type' => 'loan_full',
            'status' => 'active',
        ]);

        // Buyer manager submits full season loan bid
        $bidResponse = $this->actingAs($buyerManager)->post(route('manager.transfers.offer'), [
            'player_id' => $player->id,
            'offer_amount' => 20000,
            'deal_type' => 'loan_full',
            'notes' => 'Looking for full season loan arrangement',
        ]);

        $bidResponse->assertRedirect();
        $this->assertDatabaseHas('transfer_offers', [
            'player_id' => $player->id,
            'buying_team_id' => $buyerTeam->id,
            'selling_team_id' => $sellerTeam->id,
            'deal_type' => 'loan_full',
            'offer_amount' => 20000,
            'status' => 'pending',
        ]);

        $offer = TransferOffer::where('player_id', $player->id)->first();

        // Seller manager sends counter-offer
        $counterResponse = $this->actingAs($sellerManager)->post(route('manager.transfers.handle', $offer->id), [
            'action' => 'counter',
            'counter_amount' => 23000,
            'counter_notes' => 'We will agree to GH₵ 23,000 for the full season loan.',
        ]);

        $counterResponse->assertRedirect();
        $this->assertDatabaseHas('transfer_offers', [
            'id' => $offer->id,
            'status' => 'countered',
            'counter_amount' => 23000,
        ]);

        // Buyer manager accepts counter-offer
        $acceptResponse = $this->actingAs($buyerManager)->post(route('manager.transfers.handle', $offer->id), [
            'action' => 'accept',
        ]);

        $acceptResponse->assertRedirect();

        $this->assertDatabaseHas('transfer_offers', [
            'id' => $offer->id,
            'status' => 'accepted',
        ]);

        // Verify player transferred to buyer team
        $player->refresh();
        $this->assertEquals($buyerTeam->id, $player->team_id);

        // Verify news article generated
        $this->assertDatabaseHas('articles', [
            'tag' => 'Transfer',
        ]);
        $article = Article::latest()->first();
        $this->assertStringContainsString('LOAN SIGNING', $article->title);
    }
}
