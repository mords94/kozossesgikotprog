<?php
namespace App;

class Services
{

    private $controller;

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function userRelationShip($userid, $friendid, $status = 0)
    {
        $relationship = $this->controller->getModel()->getDatabase()->selectFromWhere(
            'user_friend',
            "(user_id = $userid AND friend_id = $friendid AND status = $status) OR (user_id = $friendid AND friend_id = $userid AND status = $status)"
            , '');

        return count($relationship) > 0;
    }

    public function userKnownRelationShip($userid, $friendid)
    {
        return $this->userRelationShip($userid, $friendid, 1);
    }


    public function userPendingRelationShip($userid, $friendid)
    {
        return $this->userRelationShip($userid, $friendid, 0);
    }
}