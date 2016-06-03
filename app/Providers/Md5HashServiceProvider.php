<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/16
 * Time: 0:41
 */

namespace App\Providers;

use App\Helper\Md5Hasher;
use Illuminate\Hashing\HashServiceProvider;

class Md5HashServiceProvider extends HashServiceProvider
{
    public function register()
    {
        $this->app->singleton('hash', function() { return new Md5Hasher(); });
    }
}