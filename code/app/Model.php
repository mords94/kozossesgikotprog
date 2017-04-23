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
        return $this->database->selectOneFromWhere('users', 'email LIKE \'' . $email . '\'');
    }

    public function user($id)
    {
        return $this->database->selectOneFromWhere('users', "id = $id");
    }

    public function register($user)
    {
        return $this->database->insert('users', $user);
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
            FROM users
              JOIN user_friend 
              ON users.id = user_friend.friend_id 
              OR users.id = user_friend.user_id
            WHERE (user_friend.user_id = $userid OR user_friend.friend_id = $userid) 
            AND users.id != $userid;"
        );
    }

    public function getAllClubs() {
        return $this->database->selectFrom('clubs');
    }

    public function saveClub($name)
    {
        return $this->database->insert('clubs', ['name' => $name]);
    }

    public function deleteClub($id) {
        return $this->database->delete('clubs', "id = $id");
    }

    public function getClubsByName($name) {
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

    public function getAllSchools() {
        return $this->database->selectFrom('school');
    }

    public function saveSchool($name)
    {
        return $this->database->insert('school', ['name' => $name]);
    }

    public function deleteSchool($id) {
        return $this->database->delete('school', "id = $id");
    }

    public function getSchoolByName($name) {
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
              ON school.id = club_id.user_id 
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

    public function getAllWorkplaces() {
        return $this->database->selectFrom('workplace');
    }

    public function saveWorkplace($name)
    {
        return $this->database->insert('workplace', ['name' => $name]);
    }

    public function deleteWorkplace($id) {
        return $this->database->delete('workplace', "id = $id");
    }

    public function getWorkplaceByName($name) {
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
              JOIN user_workplace
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
    * @param $workplace INT
    * @return array
    */

    public function recommendFriendBasedOnWorkplace($workplace)
    {
        return $this->database->selectCustom(
            "SELECT users.firstname
            FROM users
            JOIN user_work ON user_work.user_id = users.id
            JOIN user_work ON user_work.workplace_id = workplace_id
            WHERE workplace_id = $workplace;"
        );
    }

    /* Recommend users based on the school
    * @param $school INT
    * @return array
    */

    public function recommendFriendBasedOnSchool($school)
    {
        return $this->database->selectCustom(
            "SELECT users.firstname
            FROM users
            JOIN user_school ON user_school.user_id = users.id
            JOIN user_school ON user_school.school_id = school_id
            WHERE school_id= $school;"
        );
    }
}