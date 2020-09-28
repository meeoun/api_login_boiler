<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'user' => new UserResource($this->user),
          'title' => $this->title,
            'slug' => $this->slug,
            'images' => $this->images,
            'description' => $this->description,
            'team' => $this->team ? [
              'name'=> $this->team->name,
                'slug'=>$this->team->slug
            ]: null,
            'tag_list'=>[
                'tags'=>$this->tagArray,
                'normalized'=>$this->tagArrayNormalized
            ],
            'created_at_dates'=>[
                'created_at_human' => $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ]

        ];
    }
}
