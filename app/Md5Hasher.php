<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/16
 * Time: 0:42
 */

namespace App;

use RuntimeException;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class Md5Hasher implements HasherContract
{

    /**
     * Hash the given value.
     *
     * @param  string $value
     * @param  array $options
     * @return string
     */
    public function make($value, array $options = [])
    {
        $value = env('SALT', '').$value;
        return md5($value);
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string $value
     * @param  string $hashedValue
     * @param  array $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        return $this->make($value) === $hashedValue;
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string $hashedValue
     * @param  array $options
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
}