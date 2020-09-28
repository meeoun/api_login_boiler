<?php
namespace App\Repositories\Eloquent;

use App\Models\Design;
use App\Repositories\Contracts\IDesign;
use Illuminate\Http\Request;

class DesignRepository extends BaseRepository implements IDesign
{

    public function model()
    {
        return Design::class;
    }

    public function applyTags(Design $design, array $data)
    {
        $design->retag($data);

    }

    public function addComment(Design $design, array $data)
    {
        $comment = $design->comments()->Create($data);

        return $comment;
    }

    public function like(Design $design)
    {
      if($design->likedByUser(auth()->id()))
        {
            $design->unlike();
        }
      else{
          $design->like();
      }
    }

    public function isLikedByUser(Design $design)
    {
        return $design->likedByUser(auth()->id());
    }

    public function search(Request $request)
    {
        $query = (new $this->model)->newQuery();

        $query->where('is_live', true);

        if($request->has_comments){
            $query->has('comments');
        }

        if($request->has_teams){
            $query->has('teams');
        }

        if($request->q){
            $query->where(function ($q) use ($request){
               $q->where('title', 'like', '%'.$request->q.'%')
               ->orWhere('description', 'like','%'.$request->q.'%');
            });
        }

        if($request->orderBy === 'likes'){
            $query->withCount('likes')
                ->orderByDesc('likes_count');
        } else{
            $query->latest();
        }

        return $query->get();
    }

}
