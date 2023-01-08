<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\RepostRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\LikedPost;
use App\Models\Post;
use App\Models\User;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(){
        $posts = Post::where('user_id', auth()->id())->latest()->get();

        $LikedPostIds = LikedPost::where('user_id', auth()->id())
        ->get('post_id')->pluck('post_id')->toArray();

        foreach ($posts as $post) {
            if (in_array($post->id, $LikedPostIds)){
                $post->is_liked = true;
            }
        }


        return PostResource::collection($posts);
    }
    
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

    public function repost(RepostRequest $request, Post $post){

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['reposted_id'] = $post->id;

        Post::create($data);

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

    
    public function toggleLike(Post $post){
        

        $res = auth()->user()->likedPosts()->toggle($post->id);

        $data['is_liked'] = count($res['attached']) > 0;

        $data['likes_count'] = $post->likedUsers()->count();

        return $data;

    }

}
