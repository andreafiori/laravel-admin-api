<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Requests\ImageUploadRequest;

class ImageController extends Controller
{
    /**
     * @OA\Post(
     *   path="/upload",
     *   security={{"bearerAuth":{}}},
     *   tags={"Images"},
     *   @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"image"},
     *                 @OA\Property(
     *                     property="image",
     *                     type="file",
     *                     format="file",
     *                     description="Image",
     *                 ),
     *
     *             )
     *        )
     *   ),
     *   @OA\Response(response="200",
     *     description="Upload Images",
     *   )
     * )
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function upload(ImageUploadRequest $request)
    {
        // TODO upload permission \Gate::authorize('edit', 'upload');
        \Gate::authorize('edit', 'products');

        $file = $request->file('image');
        $name = Str::random(10);
        $url = \Storage::putFileAs('images', $file, $name . '.' . $file->extension());

        return [
            'url' => env('APP_URL') . '/' . $url,
        ];
    }
}
