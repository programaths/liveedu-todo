<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 17/08/17
 * Time: 20:02
 */

namespace Programaths\LiveEdu\Todo\Services;


interface TodoRepositoryInterface
{
    /**
     * @param $todoList
     * @return array|bool false on error, list of updated ids on success
     */
    function batchUpdate($todoList);

    function delete($id);

    function load($id);

    function create($todo);
}