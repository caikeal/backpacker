<?php

namespace App\Api\Controllers\v1;

use App\Api\Transformer\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Api\Controllers\BaseController;

class UserController extends BaseController
{
    public function index()
    {
        $users= User::paginate(15);
        return $this->response->paginator($users, new UserTransformer());
    }
    public function show($id)
    {
        $user=User::findOrFail($id);
        return $this->response->item($user,new UserTransformer());
    }
}
