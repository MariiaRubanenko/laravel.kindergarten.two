<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Family_account;
use App\Models\Child_profile;
use App\Models\Trusted_person;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::with('family_accounts')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new UserResource(User::with('family_accounts')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // 


        foreach ($user->family_accounts as $familyAccount) {
            $familyAccount->child_profiles()->delete();
        }
 
        foreach ($user->family_accounts as $familyAccount) {
            $familyAccount->trusted_persons()->delete();
        }


        $user->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
