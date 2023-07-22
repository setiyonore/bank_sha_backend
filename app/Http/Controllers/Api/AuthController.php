<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'pin' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }
        $user = User::query()->where('email', $request->email)->exists();
        if ($user) {
            return response()->json(['message' => 'Email Already Taken'], 409);
        }
        try {
            $profilePicture = null;
            $ktp = null;
            if($request->profile_picture){
                $profilePicture = $this->uploadBase64Image($request->profile_picture);
            }
            if($request->ktp){
                $ktp = $this->uploadBase64Image($request->ktp);
            }
        } catch (\Throwable $th) {
            echo $th;
        }
    }

    private function uploadBase64Image($image){
        $decoder = new Base64ImageDecoder($image, $allowedFormats = ['jpeg', 'png', 'jpg']);
        $decodeContent = $decoder->getDecodedContent();
        $format = $decoder->getFormat();
        $image = Str::random(10).'.'.$format;
        Storage::disk('public')->put($image,$decodeContent);
        return $image;
    }
}
