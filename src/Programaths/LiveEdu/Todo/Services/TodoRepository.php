<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 15/08/17
 * Time: 20:03
 */

namespace Programaths\LiveEdu\Todo\Services;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PDO;
use Programaths\LiveEdu\Todo\Exceptions\TodoNotFound;
use Programaths\LiveEdu\Todo\Exceptions\UserNotFound;

class TodoRepository implements TodoRepositoryInterface
{
    /**
     * @var Connection
     */
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param $userList
     * @return array|bool false on error, list of updated ids on success
     */

    function batchUpdate($userList){
        $db = $this->db;
        $db->beginTransaction();
        //$stmUpdate = $db->prepare('UPDATE todos SET nickname=:nick WHERE id=:id RETURNING id');
        $updatedIds = [];
        $fields = ['user_id','done','title','description'];
        foreach ($userList as $todoUpdate) {
            $qb = $db->createQueryBuilder();
            $qb->update('todos','t');
            if(empty($todoUpdate['id'])){
                $db->rollBack();
                return false;
            }
            foreach ($fields as $field){
                if (!empty($todoUpdate[$field])) $qb->set("u.$field" , $todoUpdate[$field]);
            }
            $qb->where($qb->expr()->eq('u.id',$todoUpdate['id']));
            $qb->execute();
            $updatedIds[] = $todoUpdate['id'];
        }
        $db->commit();
        return $updatedIds;
    }

    function delete($id){
        $this->db->prepare('DELETE FROM todos WHERE id=?')->execute([$id]);
    }

    function load($id){
        $stm = $this->db->prepare(
            'SELECT id, user_id, done, title, description FROM todos WHERE id=?'
        );
        if($stm->execute([$id])){
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if($result===false){
                throw new TodoNotFound("Todo with id $id was not found");
            }
            return $result;
        }
        throw new TodoNotFound("Todo with id $id was not found");
    }

    function create($user){
        $insertTodo = $this->db->prepare('INSERT INTO todos(user_id, done, title, description) VALUES (:user_id, :done, :title, :description) RETURNING id;');

        $insertTodo->execute([
            ':user_id'=> $user['user_id'],
            ':done'=> $user['done'],
            ':title'=> $user['title'],
            ':description' => $user['description']
        ]);

        $lid = $insertTodo->fetchColumn();
        $user['id'] = $lid;
        return $user;
    }

}