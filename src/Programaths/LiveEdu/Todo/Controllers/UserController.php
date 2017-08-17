<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 15/08/17
 * Time: 21:06
 */

namespace Programaths\LiveEdu\Todo\Controllers;


use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Programaths\LiveEdu\Todo\Exceptions\UserNotFound;
use Programaths\LiveEdu\Todo\Services\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function post(Request $request){
        $createdUser = $this->userRepo->create($request->request->all());
        return RedirectResponse::create("/api/v2-0/users/{$createdUser['id']}", Response::HTTP_CREATED);
    }

    public function all(Request $request){
        return $this->userRepo->find();
    }

    public function get(Request $request,$id){
        try {
            $user = $this->userRepo->load($id);
        }catch (UserNotFound $e){
            throw new NotFoundHttpException();
        }
        return $user;
    }
    public function patch(Request $request){
        $body = $request->request->all();
        if(!is_array($body)){
            throw new BadRequestHttpException();
        }
        try {
            $idList = $this->userRepo->batchUpdate($body);
            if ($idList === false) {
                throw new BadRequestHttpException();
            }
        }catch (UniqueConstraintViolationException $e){
            throw new BadRequestHttpException('duplicate username');
        }

        return $idList;
    }
    public function delete(Request $request,$id){
        $this->userRepo->delete($id);
        return Response::create();
    }
}