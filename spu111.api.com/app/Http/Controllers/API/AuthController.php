<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     tags={"Auth"},
     *     path="/api/register",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "lastName", "name", "phone", "image", "password", "password_confirmation"},
     *                 @OA\Property(
     *                     property="image",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="lastName",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Add Category.")
     * )
     */
    public function register(Request $request) {
        $input = $request->all();
        $validation = Validator::make($input,[
            'name'=> 'required|string',
            'lastName'=> 'required|string',
            'image'=> 'required|string',
            'phone'=> 'required|string',
            'email'=> 'required|email',
            'password'=> 'required|string|min:6',
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), Response::HTTP_BAD_REQUEST);
        }

        $imageName = uniqid().".webp";
        $sizes = [50,150,300,600,1200];
        $manager = new ImageManager(new Driver()); //менеджер для фото
        foreach ($sizes as $size) {
            $imageSave = $manager->read($input["image"]);
            $imageSave->scale(width: $size);
            $path = public_path("upload/".$size."_".$imageName);
            $imageSave->toWebp()->save($path);
        }
        $user = User::create(array_merge(
            $validation->validated(),
            ['password'=>bcrypt($input['password']), 'image'=>$imageName]
        ));
        return response()->json(['user'=>$user], Response::HTTP_OK);
    }

    // Task 1 - Log into server and return jwj-token
    /**
     * @OA\Post(
     *     tags={"Auth"},
     *     path="/api/login",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="User logged in successfully.")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Генерація та повернення JWT token
            $user = Auth::user();
            $token = $user->createToken('authToken')->accessToken;

            return response()->json(['user' => $user, 'access_token' => $token], Response::HTTP_OK);
        }

        // Помилка аутентифікації
        return response()->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
    }
}
