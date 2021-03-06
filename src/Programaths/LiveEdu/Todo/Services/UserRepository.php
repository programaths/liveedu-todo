<?php
/**
 * Created by PhpStorm.
 * User: live-edu
 * Date: 15/08/17
 * Time: 20:03
 */

namespace Programaths\LiveEdu\Todo\Services;


use Doctrine\DBAL\Driver\Connection;
use PDO;
use Programaths\LiveEdu\Todo\Exceptions\UserNotFound;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var Connection
     */
    private $db;
    /**
     * @var PasswordEncoderInterface
     */
    private $encoder;

    public function __construct(Connection $db, PasswordEncoderInterface $encoder)
    {
        $this->db = $db;
        $this->encoder = $encoder;
    }

    /**
     * @param $userList
     * @return array|bool false on error, list of updated ids on success
     */

    function batchUpdate($userList){
        $db = $this->db;
        $db->beginTransaction();
        $stmUpdate = $db->prepare('UPDATE users SET nickname=:nick WHERE id=:id RETURNING id');
        $updatedIds = [];
        foreach ($userList as $userUpdate) {
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

    function delete($id){
        $this->db->prepare('DELETE FROM users WHERE id=?')->execute([$id]);
    }

    function load($id){
        $stm = $this->db->prepare(
            'SELECT id, nickname FROM users WHERE id=?'
        );
        if($stm->execute([$id])){
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if($result===false){
                throw new UserNotFound("User with id $id was not found");
            }
            return $result;
        }
        throw new UserNotFound("User with id $id was not found");
    }

    private function createRandomPrivateKey(){
        $key='';
        for($i=0;$i<32;$i++){
            $key.=chr(65+rand(0,26));
        }
        return $key;
    }

    function create($user){
        $insertUser = $this->db->prepare('INSERT INTO users(nickname, pass,private_key) VALUES (:nick,:pass,:pkey) RETURNING id;');

        $insertUser->execute([
            ':nick' => $user['nickname'],
            ':pass' => $this->encoder->encodePassword($user['pass'],null),
            ':pkey' => $this->createRandomPrivateKey()
        ]);

        $lid = $insertUser->fetchColumn();
        $user['id'] = $lid;
        return $user;
    }

    public function find()
    {
        $stm = $this->db->prepare('SELECT id, nickname FROM users');
        if($stm->execute()===false){
            return [];
        }
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUsername($username)
    {
        $stm = $this->db->prepare('SELECT nickname,pass,private_key FROM users WHERE nickname=:nick');
        if($stm->execute([':nick'=>$username])===false){
            return null;
        }
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        if($result===false){
            return null;
        }
        return $result;
    }
}