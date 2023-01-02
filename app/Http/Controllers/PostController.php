<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function store(StoreRequest $request){

        $data = $request->validated();

        try {
            DB::beginTransaction();
  
            $imageId = $data['image_id'];
            unset($data['image_id']);

            $data['user_id'] = auth()->id();
            $post = Post::create($data);

            $this->processImage($post,$imageId);

            PostImage::clearStorage();

            DB::commit();
        } catch(\Exception $exception){
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()]);
        }

        return new PostResource($post);

    }

    private function processImage($post, $imageId){

        if(isset($imageId)) {
            $image = PostImage::find($imageId);
            $image->update([
                //'status' => true,            //необзательно лишний элемент
                'post_id' => $post->id,
            ]);
        };

    }
}
