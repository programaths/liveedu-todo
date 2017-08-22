<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 17/08/17
 * Time: 20:01
 */

namespace Programaths\LiveEdu\Todo\Controllers;


use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Programaths\LiveEdu\Todo\Exceptions\TodoNotFound;
use Programaths\LiveEdu\Todo\Services\TodoRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodoController
{
    /**
     * @var TodoRepositoryInterface
     */
    private $todoRepo;

    public function __construct(TodoRepositoryInterface $todoRepo)
    {

        $this->todoRepo = $todoRepo;
    }

    public function post(Request $request){
        $createdTodo = $this->todoRepo->create($request->request->all());
        return RedirectResponse::create("/api/v2-0/todos/{$createdTodo['id']}", Response::HTTP_CREATED);
    }
    public function get(Request $request,$id){
        try {
            $todo = $this->todoRepo->load($id);
        }catch (TodoNotFound $e){
            throw new NotFoundHttpException();
        }
        return $todo;
    }
    public function patch(Request $request){
        $body = $request->request->all();
        if(!is_array($body)){
            throw new BadRequestHttpException();
        }

        $idList = $this->todoRepo->batchUpdate($body);
        if ($idList === false) {
            throw new BadRequestHttpException();
        }

        return $idList;
    }
    public function delete(Request $request,$id){
        $this->todoRepo->delete($id);
        return Response::create();
    }

    public function all(Request $request){
        return $this->todoRepo->find();
    }
}