<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 8/08/17
 * Time: 20:27
 */

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

include __DIR__.'/../vendor/autoload.php';

$app = new \Silex\Application();
$app['debug'] = true;

$app->register(new Sorien\Provider\PimpleDumpProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' =>
        [
            'driver'    => 'pdo_pgsql',
            'host'      => 'localhost',
            'dbname'    => 'livetodo',
            'user'      => 'livetodo',
            'password'  => '1234',
            'charset'   => 'utf8',
        ],
]);

$app->get('/',function(Request $request) use($app) {
    $helloWorld = new \Programaths\LiveEdu\Todo\HelloWorld();
    return JsonResponse::create($helloWorld->helloWorld());
});

$app->post('/',function (Request $request){
    return JsonResponse::create();
});

$app->post('/api/v2-0/users',function (Request $request) use($app) {
    $user = json_decode($request->getContent(),true);

    $insertUser = $app['db']->prepare('INSERT INTO users(nickname, pass) VALUES (:nick,:pass) RETURNING id;');

    $insertUser->execute([
        ':nick' => $user['nickname'],
        ':pass' => $user['pass']
    ]);

    $lid = $insertUser->fetchColumn();

    return RedirectResponse::create("/api/v2-0/users/$lid", Response::HTTP_CREATED);
});

/**
 * @param $body
 * @param Connection $db
 * @return array|bool false on error, list of updated ids on success
 */

function updateUser($body, Connection $db){
    $db->beginTransaction();
    $stmUpdate = $db->prepare('UPDATE users SET nickname=:nick WHERE id=:id RETURNING id');
    $updatedIds = [];
    foreach ($body as $userUpdate) {
        if (empty($userUpdate['nickname']) || empty($userUpdate['id'])) {
            $db->rollBack();
            return false;
        }
        $stmUpdate->execute([
            ':nick' => $userUpdate['nickname'],
            ':id' => $userUpdate['id']
        ]);
        $id = $stmUpdate->fetchColumn();
        $updatedIds[] = $id;
    }
    $db->commit();
    return $updatedIds;
}

$app->patch('/api/v2-0/users',function(Request $request) use ($app) {
    $body = json_decode($request->getContent(),true);
    if(!is_array($body)){
        throw new BadRequestHttpException();
    }
    try {
        $idList = updateUser($body, $app['db']);
        if ($idList === false) {
            throw new BadRequestHttpException();
        }
    }catch (UniqueConstraintViolationException $e){
        throw new BadRequestHttpException('duplicate username');
    }

    return JsonResponse::create($idList);
});

$app->delete('/api/v2-0/users/{id}',function(Request $request,$id) use($app) {
    $app['db']->executeQuery(
        'DELETE FROM users WHERE id=?',
        [$id]
    );
    return Response::create();
});


$app->get('/api/v2-0/users/{id}',function(Request $request,$id) use($app) {
    $user = $app['db']->executeQuery(
        'SELECT id, nickname FROM users WHERE id=?',
        [$id]
    )->fetch(PDO::FETCH_ASSOC);
    if ($user===false){
        throw new NotFoundHttpException();
    }
    return JsonResponse::create($user);
})->assert('id','\d+');

return $app;