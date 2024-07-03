<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UsersRole;
use App\Services\AccessLogService;
use App\Services\BreadcrumbService;
use App\Services\ValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Carbon\Carbon;

class ProfileController extends Controller
{
    protected $accessLogService;
    protected $breadcrumbService;
    protected $validationService;

    public function __construct(AccessLogService $accessLogService, BreadcrumbService $breadcrumbService, ValidationService $validationService)
    {
        $this->accessLogService = $accessLogService;
        $this->breadcrumbService = $breadcrumbService;
        $this->validationService = $validationService;
    }

    public function show()
    {
        $this->accessLogService->logAccess("Meu perfil");

        $user = Auth::user();
        $created_by = User::find($user->created_by);
        $user->created_by = $created_by->name;
        $modified_by = User::find($user->modified_by);
        $user->modified_by = $modified_by->name;
        $user->date_created = Carbon::parse($user->date_created)->format('d/m/Y');
        $user->date_modified = Carbon::parse($user->date_modified)->format('d/m/Y');
        $user->cpf = preg_replace("/^(\d{3})(\d{3})(\d{3})(\d{2})$/", "$1.$2.$3-$4", $user->cpf);

        $role = UsersRole::find($user->role_id);

        $user_image = Image::where('module_id', $user->id)->where('module', 'users')->pluck('path_original');
        $user_image = $user_image->toArray();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Perfil' => 'profile.show',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Meu Perfil';

        return view('profile.profile', compact('user', 'role', 'user_image', 'breadcrumbs', 'pageTitle'));
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = Auth::user();

        return view('profile.edit', ['user' => $request->user()]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        $token = Str::random(60);
        $expiresIn = now()->addHours(24)->getTimestamp();

        $user->passwordResets()->create([
            'user_id' => $user->id,
            'date_created' => now(),
            'date_modified' => now(),
            'modified_by' => Auth::user()->id,
            'status' => 'ACTIVE',
            'expires_in' => $expiresIn,
            'token' => $token,
        ]);
        $validatedData = $request->validated();
        $user->update($validatedData);

        $request->user()->save();

        $folderPath = "public/images/users";

        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        $profile_photo = $request->file('profilePicture');
        $imageName = $profile_photo->getClientOriginalName();
        $path = $profile_photo->storeAs($folderPath, $imageName);

        Image::updateOrCreate(
            [                
                'module' => 'users',
                'module_id' => $user->id,
            ],
            [
                'status' => 'ACTIVE',
                'date_created' => now(),
                'date_modified' => now(),
                'order' => 1,
                'source' => '',
                'name_original' => $imageName,
                'path_original' => "images/users/$imageName",
                'extension' => $profile_photo->getClientOriginalExtension(),
            ]
        );

        Auth::logout();
        return Redirect::to('/login');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        return response()->json(['status' => 'SUCCESS']);
    }

    public function validateProfileRequest(ProfileUpdateRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules(), $request->messages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        return response()->json(['success' => true], 200);
    }
}
