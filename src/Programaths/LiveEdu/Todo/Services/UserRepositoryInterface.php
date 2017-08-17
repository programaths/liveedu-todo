<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 15/08/17
 * Time: 21:07
 */

namespace Programaths\LiveEdu\Todo\Services;

interface UserRepositoryInterface
{
    /**
     * @param $userList
     * @return array|bool false on error, list of updated ids on success
     */
    function batchUpdate($userList);

    function delete($id);

    function load($id);

    function create($user);

    public function find();
}