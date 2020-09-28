<?php
namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterion;

class ForUser implements ICriterion
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }


    public function apply($model)
    {
        return $model->where('user_id',$this->user);
    }
}
