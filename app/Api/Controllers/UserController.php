<?php

namespace App\Api\V1;

use App\User;
use App\Http\Requests;
use App\Api\V1\BaseController;

class UserController extends BaseController
{
    public function index()
    {
        $users= User::all();
        return $this->response->collection($users, new UserTransformer);
    }
    public function show($id)
    {
        $user=User::findOrFail($id);
        return $this->response->array($user->toArray());
//        return $this->response->array($user->toArray());
//        return $this->response->item($user, new UserTransformer);
    }
}
