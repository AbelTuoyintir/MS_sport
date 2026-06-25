<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeamRegistrationController extends Controller
{
    public function showForm()
    {
        return view('welcome');
    }

    public function storeTeamInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team_name' => 'required|string|max:100|unique:teams,team_name',
            'team_size' => 'required|in:11,15,18,23,25,30',
            'team_division' => 'required|in:premier,div1,div2,amateur',
            'primary_color' => 'required|regex:/^#[a-f0-9]{6}$/i',
            'secondary_color' => 'required|regex:/^#[a-f0-9]{6}$/i',
            'accent_color' => 'nullable|regex:/^#[a-f0-9]{6}$/i',
            'password' => 'required|min:8|confirmed',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $teamData = $request->only([
            'team_name',
            'team_size',
            'team_division',
            'primary_color',
            'secondary_color',
            'accent_color',
        ]);

        $teamData['division'] = $teamData['team_division'];
        unset($teamData['team_division']);

        $teamData['password'] = Hash::make($request->password);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('team-logos', 'public');
            $teamData['logo_path'] = $path;
        }

        session(['team_registration' => $teamData]);

        return response()->json([
            'success' => true,
            'message' => 'Team information saved successfully',
        ]);
    }

    public function storeOwners(Request $request)
    {
        $owners = $request->input('owners', []);

        if (empty($owners)) {
            return response()->json([
                'errors' => ['owners' => 'At least one owner is required'],
            ], 422);
        }

        $validatorRules = [];
        foreach ($owners as $index => $owner) {
            $validatorRules["owners.{$index}.full_name"] = 'required|string|max:255';
            $validatorRules["owners.{$index}.email"] = 'required|email|max:255';
            $validatorRules["owners.{$index}.phone"] = 'required|string|max:20';
            $validatorRules["owners.{$index}.ownership_percentage"] = 'required|integer|min:1|max:100';
        }

        $validator = Validator::make($request->all(), $validatorRules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $totalPercentage = array_sum(array_column($owners, 'ownership_percentage'));
        if ($totalPercentage !== 100) {
            return response()->json([
                'errors' => ['ownership_percentage' => 'Total ownership percentage must equal 100%'],
            ], 422);
        }

        session(['team_registration.owners' => $owners]);

        return response()->json([
            'success' => true,
            'message' => 'Owners information saved successfully',
        ]);
    }

    public function createTeamFromSession()
    {
        $teamData = session('team_registration');

        if (!$teamData || !isset($teamData['owners'])) {
            return null;
        }

        try {
            DB::beginTransaction();

            $team = Team::create([
                'reference_code' => Team::generateReferenceCode(),
                'team_name' => $teamData['team_name'],
                'team_size' => $teamData['team_size'],
                'division' => $teamData['division'],
                'primary_color' => $teamData['primary_color'],
                'secondary_color' => $teamData['secondary_color'],
                'accent_color' => $teamData['accent_color'] ?? null,
                'logo_path' => $teamData['logo_path'] ?? null,
                'password' => $teamData['password'],
                'registration_status' => 'submitted',
                'submitted_at' => now(),
            ]);

            foreach ($teamData['owners'] as $index => $ownerData) {
                Owner::create([
                    'team_id' => $team->id,
                    'full_name' => $ownerData['full_name'],
                    'ownership_percentage' => $ownerData['ownership_percentage'],
                    'email' => $ownerData['email'],
                    'phone' => $ownerData['phone'],
                    'is_primary' => $index === 0,
                ]);
            }

            DB::commit();

            session()->forget('team_registration');

            return $team->load(['owners', 'payment']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getTeamStatus($referenceCode)
    {
        $team = Team::where('reference_code', $referenceCode)
            ->with(['owners', 'payment'])
            ->firstOrFail();

        return response()->json([
            'team' => $team,
            'status' => $team->registration_status,
            'payment_status' => optional($team->payment)->status,
        ]);
    }
}
