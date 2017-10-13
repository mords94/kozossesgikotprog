<?php

namespace App;

use \Library\BaseModel;

class Model extends BaseModel
{
    public function fixSequences()
    {
        $tables = [
            "comment",
            "clubs",
            "users",
            //"message",
            "photo",
            "school",
            "workplace",
        ];

        foreach ($tables as $table) {
            $sql = "select setval('\"public." . $table . "_id_seq\"'::REGCLASS, max(id)) FROM $table";
            $this->database->exec($sql);
        }

    }

    public function getUserByEmail($email)
    {
        return $this->database->selectOneFromWhere('users', 'email LIKE \'' . $email . '\'');
    }

    public function user($id)
    {
        return $this->database->selectOneFromWhere('users', "id = $id");
    }

    public function updateUserSchool($userid, $schools)
    {
        $this->database->delete('user_school', 'user_id = ' . $userid);
        foreach ($schools as $school) {
            $this->database->insert('user_school', ['user_id' => $userid, 'school_id' => $school]);
        }

        return true;

    }

    public function updateUserWorkplace($userid, $workplaces)
    {
        $this->database->delete('user_work', 'user_id = ' . $userid);
        foreach ($workplaces as $workplace) {
            $this->database->insert('user_work', ['user_id' => $userid, 'workplace_id' => $workplace]);
        }

        return true;
    }

    public function updateUserClub($userid, $clubs)
    {
        $this->database->delete('club_member', 'user_id = ' . $userid);
        foreach ($clubs as $club) {
            $this->database->insert('club_member', ['user_id' => $userid, 'club_id' => $club]);
        }

        return true;

    }

    public function updateUser($userid, $data)
    {
        return $this->database->update('users', $data, 'id = ' . $userid);
    }

    public function addProfilePicture($userid, $pictureid)
    {
        return $this->database->update('users', ['photo_id' => $pictureid], 'id = ' . $userid);
    }

    public function uploadPhoto($src, $title = 'Profilkép')
    {
        return $this->database->insert('photo', ['title' => $title, 'src' => $src]);
    }

    public function register($user)
    {
        return $this->database->insert('users', $user);
    }


    public function sendFriendRequest($userid, $friendid)
    {
        return $this->database->insert('user_friend', ['user_id' => $userid, 'friend_id' => $friendid]);
    }

    public function approveFriendRequest($userid, $friendid)
    {
        return $this->database->update(
            'user_friend',
            [
                'status' => STATUS_APPROVED,
            ],
            'user_id = ' . $friendid . ' AND friend_id = ' . $userid . ' AND status = ' . STATUS_WAITING
        );
    }

    public function getFriendRequests($userid)
    {
        return $this->database->selectCustom(
            "SELECT DISTINCT id,email,firstname,lastname
            FROM users
              JOIN user_friend 
              ON users.id = user_friend.friend_id 
              OR users.id = user_friend.user_id
            WHERE (user_friend.friend_id = $userid) 
            AND users.id != $userid AND user_friend.status =" . STATUS_WAITING . ";"
        );
    }

    public function getFriends($userid)
    {
        return $this->database->selectCustom(
            "SELECT DISTINCT id,email,firstname,lastname,photo_id
            FROM users
              JOIN user_friend 
              ON users.id = user_friend.friend_id 
              OR users.id = user_friend.user_id
            WHERE (user_friend.user_id = $userid OR user_friend.friend_id = $userid) 
            AND users.id != $userid AND user_friend.status =" . STATUS_APPROVED . ";"
        );
    }

    public function removeFriendRelation($userid, $friendid)
    {
        $this->database->delete(
            'user_friend',
            "((user_friend.user_id = $userid AND user_friend.friend_id = $friendid) 
            OR 
            (user_friend.user_id = $friendid AND user_friend.friend_id = $userid))");
    }

    public function getAllClubs()
    {
        return $this->database->selectFrom('clubs');
    }

    public function saveClub($name)
    {
        return $this->database->insert('clubs', ['name' => $name]);
    }

    public function deleteClub($id)
    {
        return $this->database->delete('clubs', "id = $id");
    }

    public function getClubsByName($name)
    {
        return $this->database->selectFromWhere('clubs', "name = '$name'");
    }

    /*Collect the query user's clubs in an array *
     * @param $userid int
     * @return array
     */
    public function getClubs($userid)
    {
        return $this->database->selectCustom(
            "SELECT clubs.*
            FROM clubs
              JOIN club_member
              ON clubs.id = club_member.club_id 
            AND club_member.user_id = $userid;"
        );
    }


    /*Collect the query club's members in an array *
     * @param $clubid int
     * @return array
     */

    public function getClubMembers($clubid)
    {
        return $this->database->selectCustom(
            "SELECT users.*
            FROM club_member
               JOIN users ON users.id = club_member.user_id
            AND club_member.club_id = $clubid;"
        );
    }

    public function addMemberToClub($club, $user)
    {
        return $this->database->insert('club_member', ['club_id' => $club, 'user_id' => $user]);
    }

    public function getAllSchools()
    {
        return $this->database->selectFrom('school');
    }

    public function saveSchool($name)
    {
        return $this->database->insert('school', ['name' => $name]);
    }

    public function deleteSchool($id)
    {
        return $this->database->delete('school', "id = $id");
    }

    public function getSchoolByName($name)
    {
        return $this->database->selectFromWhere('school', "name = '$name'");
    }


    /*Collect the query user's schools in an array *
     * @param $userid int
     * @return array
     */
    public function getSchools($userid)
    {
        return $this->database->selectCustom(
            "SELECT school.*
            FROM school
              JOIN user_school
              ON school.id = user_school.school_id 
            AND user_school.user_id = $userid;"
        );
    }

    /*Collect the query school's members in an array *
     * @param $schoolid int
     * @return array
     */

    public function getSchoolMembers($schoolid)
    {
        return $this->database->selectCustom(
            "SELECT users.*
            FROM user_school
               JOIN users ON users.id = user_school.user_id
            AND user_school.school_id = $schoolid;"
        );
    }

    public function addUserToSchool($school, $user)
    {
        return $this->database->insert('user_school', ['school_id' => $school, 'user_id' => $user]);
    }

    public function getAllWorkplaces()
    {
        return $this->database->selectFrom('workplace');
    }

    public function saveWorkplace($name)
    {
        return $this->database->insert('workplace', ['name' => $name]);
    }

    public function deleteWorkplace($id)
    {
        return $this->database->delete('workplace', "id = $id");
    }

    public function getWorkplaceByName($name)
    {
        return $this->database->selectFromWhere('workplace', "name = '$name'");
    }

    /*Collect the query user's workplaces in an array *
     * @param $userid int
     * @return array
     */
    public function getWorkplaces($userid)
    {
        return $this->database->selectCustom(
            "SELECT workplace.*
            FROM workplace
              JOIN user_work
              ON workplace.id = user_work.workplace_id
            AND user_work.user_id = $userid;"
        );
    }

    /*Collect the query workplace's members in an array *
     * @param $workplaceid int
     * @return array
     */

    public function getWorkplaceMembers($workplaceid)
    {
        return $this->database->selectCustom(
            "SELECT users.*
            FROM user_workplace
               JOIN users ON users.id = user_workplace.user_id
            AND user_workplace.workplace_id = $workplaceid;"
        );
    }

    public function addUserToWorkplace($workplace, $user)
    {
        return $this->database->insert('user_workplace', ['workplace_id' => $workplace, 'user_id' => $user]);
    }

    /* Count the amount of users in the query's club
    * @param $clubid int
    * @return int
    */

    public function countClubMembers($clubid)
    {
        return $this->database->selectCustom(
            "SELECT COUNT(*)
            FROM club_member
            WHERE club_id = $clubid;"
        );
    }

    /* List the users with the same birthdate as given
    * @param $date DATE
    * @return array
    */

    public function listBirthday($date)
    {
        return $this->database->selectCustom(
            "SELECT DISTINCT firstname, lastname, birthdate
            FROM users
            WHERE datepart(m,users.birthdate) = $date;"
        );
    }

    /* Recommend users based on the workplace
  * @param $school INT
  * @return array
  */

    public function recommendFriendBasedOnWorkplace($me, array $friends, array $workplaces)
    {
        $friends = empty($friends) ? "" : "AND users.id NOT IN (" . implode(',', $friends) . ")";
        $workplaces = implode(',', $workplaces);

        return $this->database->selectCustom(
            "SELECT DISTINCT  users.*
                FROM users
                  JOIN user_work ON user_work.user_id = users.id
                WHERE workplace_id IN ($workplaces) 
                  AND users.id != $me 
                  $friends ORDER BY users.firstname;"
        );
    }

    /* Recommend users based on the school
    * @param $school INT
    * @return array
    */

    public function recommendFriendBasedOnSchool($me, array $friends, array $schools)
    {
        $schools = implode(',', $schools);
        $friends = empty($friends) ? "" : "AND users.id NOT IN (" . implode(',', $friends) . ")";

        return $this->database->selectCustom(
            "SELECT DISTINCT  users.*
            FROM users
              JOIN user_school ON user_school.user_id = users.id
            WHERE school_id IN ($schools) 
              AND users.id != $me 
                $friends ORDER BY users.firstname;"
        );
    }

    /* Recommend users based on the clubs
    * @param $school INT
    * @return array
    */

    public function recommendFriendBasedOnClub($me, array $friends, array $clubs)
    {
        $clubs = implode(',', $clubs);
        $friends = empty($friends) ? "" : "AND users.id NOT IN (" . implode(',', $friends) . ")";

        return $this->database->selectCustom(
            "SELECT DISTINCT  users.*
            FROM users
              JOIN club_member ON club_member.user_id = users.id
            WHERE club_id IN ($clubs) 
              AND users.id != $me 
                $friends ORDER BY users.firstname;"
        );
    }

    /* List all users from the website
    * @param -
    * @return array
    */

    public function getAllUsers()
    {
        return $this->database->selectCustom(
            "SELECT users.*
            FROM users ORDER BY users.firstname;"
        );
    }

    public function getPhoto($id)
    {
        return $this->database->selectOneFromWhere('photo', 'id = ' . $id);
    }

    public function addComment($comment) {
        return $this->database->insert('comment', $comment);
    }

    public function getCommentsByPhotoId($id)
    {
        $comments = $this->database->selectFromWhere('comment', 'photo_id = ' . $id);

        foreach ($comments as $i => $comment) {
            $comments[$i]['user'] = $this->user($comment['user_id']);
            $user = $this->user($comment['user_id']);
            if ($user['photo_id'] != null) {
                $photo = $this->getPhoto($user['photo_id']);

            } else {
                $photo = ['title' => 'Default image', 'src' => '/assets/images/user.png'];
            }
            $comments[$i]['user']['photo'] = $photo;
        }

        return $comments;
    }
}