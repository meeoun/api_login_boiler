<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Designs\DeleteDesign;
use App\Http\Requests\Designs\DesignUpdate;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\ForUser;
use App\Repositories\Eloquent\Criteria\IsLive;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{

    protected $designs;

    public function __construct(IDesign $designs)
    {
        $this->designs = $designs;
    }


    public function index()
    {
        $designs = $this->designs->withCriteria([
            new LatestFirst(),
            new IsLive(),
            new ForUser(1),
            new EagerLoad(['designs'])
        ])->all();
        return DesignResource::collection($designs);
    }

    public function show(Design $design)
    {
        return new DesignResource($design);
    }

    public function like(Design $design)
    {
        $this->designs->like($design);

        return response()->json(['message'=>'successful'],200);
    }


    public function update(DesignUpdate $request, Design $design)
    {
        $design=$this->
        designs->
        update($design,[
            "team_id" => $request->team,
            "title"=>$request->title,
            "description"=>$request->description,
            "slug"=> Str::slug($request->title),
            "is_live"=>! $design->upload_successful ? false : $request->is_live]);

        $this->designs->applyTags($design, $request->tags);

        return new DesignResource($design);

    }


    public function destroy(DeleteDesign $request, Design $design)
    {

        foreach(['thumbnail','large', 'original'] as $size)
        {
            if(Storage::disk($design->disk)->exists("uploads/designs/{$size}/".$design->image))
            {
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/".$design->image);
            }
        }
        $this->designs->delete($design);

        return response()->json(['message' => 'Record Deleted'], 200);

    }


    public function userLikes(Design $design)
    {
        $result = $this->designs->isLikedByUser($design);

        return response()->json(['liked'=> $result],200);
    }

    public function search(Request $request)
    {
        $designs = $this->designs->search($request);

        return DesignResource::collection($designs);
    }
}
