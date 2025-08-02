<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\CreateImageProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\user;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;


class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {

        $this->profileService = $profileService;

    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        $updatedProfile = $this->profileService->update($user, $validated);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'data' => new ProfileResource($updatedProfile),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function storeProfileImage(CreateImageProfileRequest $request)
    {
        $user = auth()->user();

        $updated = $this->profileService->updateProfileImage($user, $request->validated() + [
            'profile_image' => $request->file('profile_image'),
        ]);

        return response()->json([
            'message' => 'Imagen de perfil actualizada correctamente',
            'data' => new ProfileResource($updated),
        ], 200);
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
    public function show(user $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $user = auth()->user();
        $this->profileService->delete($user);
        return response()->json([
            'message' => 'Perfil eliminado correctamente'
        ]);
    }
}
 