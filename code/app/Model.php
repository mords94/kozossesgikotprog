<?php

/**
 * Created by PhpStorm.
 * User: drava
 * Date: 2017. 03. 30.
 * Time: 1:44
 */
class Model extends BaseModel
{
   public function getUserByEmail($email)
   {
       return $this->database->selectOneFromWhere('users','email LIKE \''.$email.'\'');
   }

    public function user($id)
    {
        return $this->database->selectOneFromWhere('users',"id = $id");
    }


   public function addFriend($userid, $friendid)
   {
       return $this->database->insert('user_friend', ['user_id' => $userid, 'friend_id' => $friendid]);
   }

   /**
    * Query user's friend ids with the id of $userid
    * @param $userid int
    * @return array
    */
   public function getFriends($userid)
   {
       return $this->database->selectCustom(
           "SELECT DISTINCT id,email,firstname,lastname
            FROM user_friend
              JOIN users 
              ON users.id = user_friend.friend_id 
              OR users.id = user_friend.user_id
            WHERE (user_friend.user_id = $userid OR user_friend.friend_id = $userid) 
            AND users.id != $userid;"
       );
   }
   
   
}