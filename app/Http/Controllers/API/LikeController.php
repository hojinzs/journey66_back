<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Place;
use App\PlaceRecommend;
use App\PlaceTagComment;
use App\Like;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function place(Request $request, Place $place)
    {
        $current = $this->getCurrnet($place, $request->user());
        return $this->toggleLike($place, $request->user(), $current);
    }

    public function placeRecommend(Request $request, PlaceRecommend $placeRecommend)
    {
        $current = $this->getCurrnet($placeRecommend, $request->user());
        return $this->toggleLike($placeRecommend, $request->user(), $current);
    }

    public function placeTagCommment(Request $request, PlaceTagComment $placeTagComment)
    {
        $current = $this->getCurrnet($placeTagComment, $request->user());
        return $this->toggleLike($placeTagComment, $request->user(), $current);
    }

    /**
     * Get Current like data
     *
     * @param Model $model
     * @param User $user
     * @return Model
     */
    private function getCurrnet(Model $model, User $user)
    {
        return $model->likes()->where('user_id',$user->id)->first();
    }

    /**
     * Toggle Like
     *
     * @param Model $requestModel
     * @param User $user
     * @param Model|null $currentModel
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    private function toggleLike(Model $requestModel, User $user, Model $currentModel = null)
    {
        if($currentModel == null)
        {
            $like = new Like;
            $like->user()->associate($user);
            $requestModel->likes()->save($like);
            return response("stored",201);
        } else {
            $currentModel->delete();
            return response("destroyed",200);
        }
    }
}
