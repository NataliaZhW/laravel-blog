<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\RegisterPostRequest;
use App\Models\User;
use App\Models\UserImages;
use App\Repository\User\UserRepository;
use App\Repository\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function registerView(): View
    {
        return view('auth.register');
    }

    public function register(RegisterPostRequest $registerRequest): RedirectResponse
    {
        $validated = $registerRequest->validated();
        $basePath = 'storage/images/';

        if (empty($validated)) {
            throw new BadRequestHttpException();
        }

        DB::beginTransaction();
        try {
            $userDTO = new UserDTO($validated['name'], $validated['email'], $validated['password']);
            $user = $this->userRepository->store($userDTO);

            foreach ($registerRequest->files as $images) {
                /** @var UploadedFile $image */
                foreach ($images as $image) {
                    $fileName = md5(md5(Str::random()))  . '.jpeg';

                    $image->move(storage_path('app/public/images'), $fileName);
                    $newUserImage = new UserImages();
                    $newUserImage->user_id = $user->id;
                    $newUserImage->path = $basePath . $fileName;
                    $newUserImage->save();
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            Log::critical($exception->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors([$exception->getMessage()]);
        }


        return redirect()->route('login');
    }

    public function loginView(): View
    {
        $user = User::query()->findOrFail(53)->load('images');

        return view('auth.login', ['user' => $user]);
    }

    public function login(Request $request): RedirectResponse
    {
        $user = User::query()->where('email', $request->get('email'))->first();
        if ($user && Hash::check($request->get('password'), $user->password)) {
            Auth::login($user);
        } else {
            return redirect()->back()->withErrors(['password' => 'Wrong password']);
        }

        return redirect()->route('home');
    }
}
