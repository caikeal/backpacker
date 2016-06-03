<?php

namespace App\Api\Controllers\v1;

use App\Api\Transformer\UserTransformer;
use App\Model\User;
use Illuminate\Http\Request;
use App\Api\Controllers\BaseController;

/**
 * User resource representation.
 *
 * @Resource("User", uri="/user")
 */
class UserController extends BaseController
{
    /**
     * Show all users
     *
     * Get a JSON representation of all the registered users.
     *
     * @Get("/")
     * @Versions({"v1"})
     */
    public function index()
    {
        $users= User::paginate(15);
        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * @Request("username=foo&password=bar", contentType="application/x-www-form-urlencoded")
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $user=User::findOrFail($id);
        return $this->response->item($user, new UserTransformer());
    }
}
