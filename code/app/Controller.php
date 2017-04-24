<?php


/**
 * A Feladat: SSADM terv elkészítése Közösségi weboldalhoz
 * Felhasználók regisztrálása, profilok létrehozása
 * Fényképek feltöltése, megjegyzés hozzáfűzése
 * Ismerősök bejelölése, ismeretség visszaigazolása
 * Üzenet küldése ismerősöknek
 * Klubok, csoportok alapítása
 * Klubok tagjainak létszáma
 * Ismeretlen tagok ajánlása ismerősnek közös ismerősök alapján
 * Névnaposok, születésnaposok az adott hónapban
 * Klubok ajánlása, ahol van közös ismerős
 * Ismerősök ajánlása munkahely, vagy iskola alapján
 * Üzenetek küldése, fogadása
 */

class Controller extends BaseController
{

    /**
     * ACTION: /home
     * VERB: GET
     *
     * @param Request
     * @return View
     */
    public function home(Request $request)
    {
        return view('home');
    }

    /**
     * ACTION: /logout
     * VERB: GET
     *
     * @param Request
     * @return String
     */
    public function logout(Request $request)
    {
        Session::destroy();

        redirect('/home');
    }

    public function register(Request $request)
    {

        if(strlen($request->post('password')) < 5 || strlen($request->post('password')) > 20) {
            return view("home", [
                'login_message' => 'A jelszó hossza nem megfelelő!',
            ]);
        }


        if ($request->post('password') != $request->post('password_again')) {
            return view("home", [
                'login_message' => 'Nem egyezik a két jelszó!',
            ]);
        }

        $user = [
            'firstname' => $request->post('firstname'),
            'lastname'  => $request->post('lastname'),
            'password'  => md5($request->post('password')),
            'gender'    => $request->post('gender'),
            'birthdate' => $request->post('birthdate'),
            'email'     => $request->post('email'),
        ];

        foreach($user as $attr) {
            if(empty($attr)) {
                return view("home", [
                    'login_message' => 'Üresen maradt legalább egy kötelező mező!',
                ]);
            }
        }


        $email = $this->model->getUserByEmail($user['email']);

        if (count($email) > 1) {
            return view("home", [
                'login_message' => 'Ezzel az e-mail címmel már regisztráltak!',
            ]);
        }

        if ($this->model->register($user)) {
            return view("home", [
                'login_message' => 'Sikeres regisztráció!',
            ]);
        } else {
            return view("home", [
                'login_message' => 'A regisztráció sikertelen volt!',
            ]);
        }
    }

    /**
     * ACTION: /login
     * VERB: POST
     *
     * @param Request
     * @return View
     */
    public function login(Request $request)
    {
        $email = $request->post('email');
        $password = $request->post('password');

        $user = $this->model->getUserByEmail($email);

        if (!$user) {
            return view("home", [
                'login_message' => 'Ezzel a címmel nem találtam felhasználót!',
            ]);
        }

        if ($user['password'] != md5($password) && $user['password'] != $password) {
            return view("home", [
                'login_message' => 'A jelszó hibás!',
            ]);
        }

        Auth::getInstance()->register($user);

        redirect('/home');
    }

    /**
     * ACTION: /ownprofile
     * VERB: GET
     *
     * @param Request
     * @return View
     */
    public function ownProfile(Request $request)
    {
        secure();

        $user = $this->model->getUserByEmail(Auth::user()['email']);
        $allSchool = $this->model->getAllSchools();
        $schools = $this->model->getSchools($user['id']);

        foreach ($allSchool as $index=>$school) {

            $selected = false;
            foreach($schools as $userschool) {
                if($userschool['id'] == $school['id']) {
                    $selected = true;
                }
            }

            $allSchool[$index]['selected'] = $selected;
        }

        $message = $request->has('message') ? $request->get('message') : '';

        return view('form/profile',
            [
                'user' => $user,
                "schools" => $allSchool,
                'message' => $message
            ]
        );
    }

    /**
     * ACTION: /profile/{user}
     * VERB: GET
     *
     * @param Request
     * @return View
     */
    public function profile(Request $request)
    {
        secure();

        $user = $this->model->user($request->get(0));
        $allSchool = $this->model->getAllSchools();
        $schools = $this->model->getSchools($user['id']);

        foreach ($allSchool as $index=>$school) {

            $selected = false;
            foreach($schools as $userschool) {
                if($userschool['id'] == $school['id']) {
                    $selected = true;
                }
            }

            $allSchool[$index]['selected'] = $selected;
        }

        $message = $request->has('message') ? $request->get('message') : '';

        return view('profile',
            [
                'user' => $user,
                "schools" => $allSchool,
                'message' => $message
            ]
        );
    }


    /**
     * ACTION: /update_profile
     * VERB: POST
     *
     * @param Request
     * @return View
     */
    public function update_profile(Request $request)
    {
        secure();
        $userid = Auth::user()['id'];
        $data = [
            'firstname' => $request->post('firstname'),
            'lastname'  => $request->post('lastname'),
            'gender'    => $request->post('gender'),
        ];

        if(!preg_match('/^\d{1,2}\-\d{1,2}\-\d{4}$/'))

        $schools = $request->post('schools');

        if(!$this->model->updateUser($userid, $data)) {
            return view('inc/error', [
                'message' => 'Adatbázis hiba. Nem sikerült frissíteni a felhasználót.'
            ]);
        }



        if(!$this->model->updateUserSchool($userid, $schools)) {
            return view('inc/error', [
                'message' => 'Adatbázis hiba. Nem sikerült frissíteni a felhasználó iskoláit.'
            ]);
        }

        redirect('/ownprofile',
            [
                'message' => 'Sikerült elmenteni.'
             ]
        );
    }

    /**
     * ACTION: /friends
     * ACTION: /friends/{user}
     * VERB: POST
     *
     * @param Request
     * @return View
     */
    public function friends(Request $request)
    {
        secure();

        $message = '';

        if ($request->has('message')) {
            $message = $request->get('message');
        }

        $user = $request->has(0) ? $request->get(0) : Auth::user()['id'];


        $friends = $this->model->getFriends($user);

        return view(
            'friends',
            [
                'friends' => $friends,
                'message' => $message,
            ]
        );
    }

    /**
     * ACTION: /addFriend
     * VERB: POST
     *
     * @param Request
     * @return View
     */
    public function addFriend(Request $request)
    {
        secure();

        if ($request->has('friend')) {
            $friendid = $request->post('friend');
            $userid = Auth::user()['id'];

            if ($userid == $friendid) {
                redirect('/friends', ['message' => 'Magadat nem jelölheted meg...']);
            }

            // check if relationship exists between the users
            $relationship = $this->database()->selectFromWhere(
                'user_friend',
                "(user_id = $userid AND friend_id = $friendid) OR (user_id = $friendid AND friend_id = $userid)"
            );

            // if not exists insert one
            if (count($relationship) == 0) {
                if ($this->model->addFriend($userid, $friendid)) {
                    redirect('/friends', ['message' => 'Sikeresen barátnak jelölted-']);
                } else {
                    redirect('/friends', ['message' => 'Sikertelen jelölés! Adatbázis hiba!']);
                }
            } else {
                redirect('/friends', ['message' => 'Már barátok vagytok...']);
            }
        }
    }

    /**
     * ACTION: /friends
     * ACTION: /friends/{user}
     * VERB: POST
     *
     * @param Request
     * @return View
     */
    public function findfriends(Request $request)    //csak egy próbálkozás munkahely alapján barátok kilistázására
    {
        secure();

        $userid = Auth::user()['id'];

        $schools = $this->model->getSchools($userid);

        $workplaces = $this->model->getWorkplaces($userid);

        $friends = $this->model->getFriends($userid);
        $friendIds = [];
        foreach($friends as $friend) {
            $friendIds[] = $friend['id'];
        }
        $schoolIds = [];
        foreach($schools as $school) {
            $schoolIds[] =  $school['id'];
        }

        $workIds = [];
        foreach($workplaces as $work) {
            $workIds[] = $work['id'];
        }

        $byschool = [];

        $bywork = [];


        if(!empty($workIds) and !empty($schoolIds) and !empty($friendIds)) {

        $byschool = $this->model->recommendFriendBasedOnSchool($userid, $friendIds, $schoolIds);

        $bywork = $this->model->recommendFriendBasedOnWorkplace($userid, $friendIds, $workIds);
        }

        return view('recommend',
            [
                'bywork' => $bywork,
                'byschool' => $byschool,
            ]
        );
    }

    /**
     * ACTION: /newclub
     * VERB: GET
     *
     * @param Request
     * @return String
     */
    public function newclub(Request $request)
    {
        secure();

        $clubs = $this->model->getAllClubs();

        return view("form/clubs", [
            'clubs' => $clubs
        ]);
    }

    /**
     * ACTION: /delete_club
     * VERB: GET
     *
     * @param Request
     * @return String
     */
    public function delete_club(Request $request)
    {
        secure();

        $clubsID = $request->get(0);
        $this->model->deleteClub($clubsID);

        redirect('/newclub');

    }

    /**
     * ACTION: /store_club
     * VERB: GET
     *
     * @param Request
     * @return String
     */
    public function store_club(Request $request)
    {
        secure();

        $club = $request->post('club');

        if($this->model->getClubsByName($club)) {
            return view('inc/error', [
                'message' => 'Van már ilyen nevű klub.'
            ]);
        }
        $result = $this->model->saveClub($club);

        if($result) {
            redirect('/newclub');
        } else {
            return view('inc/error', [
                'message' => 'Nem sikerült elmenteni a klubot. Adatbázis hiba.'
            ]);
        }
    }

    /**
     * ACTION: /newschool
     * VERB: GET
     *
     * @param Request
     * @return String
     */
    public function newschool(Request $request)
    {
        secure();

        $schools = $this->model->getAllSchools();

        return view("form/school", [
            'schools' => $schools
        ]);
    }

    /**
     * ACTION: /delete_school
     * VERB: GET
     *
     * @param Request
     * @return String
     */
    public function delete_school(Request $request)
    {
        secure();

        $schoolsID = $request->get(0);
        $this->model->deleteSchool($schoolsID);

        redirect('/newschool');

    }

    /**
     * ACTION: /store_school
     * VERB: GET
     *
     * @param Request
     * @return String
     */
    public function store_school(Request $request)
    {
        secure();

        $school = $request->post('school');

        if($this->model->getSchoolByName($school)) {
            return view('inc/error', [
                'message' => 'Van már ilyen iskola.'
            ]);
        }
        $result = $this->model->saveSchool($school);

        if($result) {
            redirect('/newschool');
        } else {
            return view('inc/error', [
                'message' => 'Nem sikerült elmenteni az iskolát. Adatbázis hiba.'
            ]);
        }
    }

    public function newworkplace(Request $request)
    {
        secure();

        $workplaces = $this->model->getAllWorkplaces();

        return view("form/workplace", [
            'workplaces' => $workplaces
        ]);
    }

    /**
     * ACTION: /store_workplace
     * VERB: GET
     *
     * @param Request
     * @return String
     */
    public function store_workplace(Request $request)
    {
        secure();

        $workplace = $request->post('workplace');

        if($this->model->getWorkplaceByName($workplace)) {
            return view('inc/error', [
                'message' => 'Van már ilyen munkahely.'
            ]);
        }
        $result = $this->model->saveWorkplace($workplace);

        if($result) {
            redirect('/newworkplace');
        } else {
            return view('inc/error', [
                'message' => 'Nem sikerült elmenteni a munkahelyet. Adatbázis hiba.'
            ]);
        }
    }

    public function delete_workplace(Request $request)
    {
        secure();

        $workplacesID = $request->get(0);
        $this->model-> deleteWorkplace($workplacesID);

        redirect('/newworkplace');

    }

    /*ACTION: /addClubMember
     * VERB: POST
     *
     * @param Request
     * @return View
     */
    public function addClubMember(Request $request)
    {
        secure();

        if ($request->has('club')) {
            $clubid = $request->post('club');
            $userid = Auth::user()['id']; //logged in user


            // check if relationship exists between the user and the club
            $relationship = $this->model->getDatabase()->selectFromWhere(
                'club_member',
                "(user_id = $userid AND club_id = $clubid)"
            );

            // if not exists insert one
            if (count($relationship) == 0) {
                if ($this->model->addMemberToClub($userid,$clubid)) {
                    redirect('/club_member', ['message' => 'Sikeresen csatlakoztál a klubhoz!']);
                } else {
                    redirect('/club_member', ['message' => 'Sikertelen csatlakozás! Adatbázis hiba!']);
                }
            } else {
                redirect('/club_member', ['message' => 'Már tagja vagy a klubnak...']);
            }
        }
    }

    /*ACTION: /addSchoolMember
     * VERB: POST
     *
     * @param Request
     * @return View
     */
    public function addSchoolMember(Request $request)
    {
        secure();

        if ($request->has('school')) {
            $schoolid = $request->post('school');
            $userid = Auth::user()['id']; //logged in user


            // check if relationship exists between the user and the club
            $relationship = $this->model->getDatabase()->selectFromWhere(
                'user_school',
                "(user_id = $userid AND school_id = $schoolid)"
            );

            // if not exists insert one
            if (count($relationship) == 0) {
                if ($this->model->addUserToSchool($schoolid,$userid)) {
                    redirect('/user_school', ['message' => 'Sikeresen felvetted az iskolát!']);
                } else {
                    redirect('/user_school', ['message' => 'Sikertelen felvétel! Adatbázis hiba!']);
                }
            } else {
                redirect('/user_school', ['message' => 'Már felvetted az iskolát...']);
            }
        }
    }

    /*ACTION: /addWorkplaceMember
     * VERB: POST
     *
     * @param Request
     * @return View
     */
    public function addWorkplaceMember(Request $request)
    {
        secure();

        if ($request->has('workplace')) {
            $workplaceid = $request->post('workplace');
            $userid = Auth::user()['id']; //logged in user


            // check if relationship exists between the user and the club
            $relationship = $this->model->getDatabase()->selectFromWhere(
                'user_workplace',
                "(user_id = $userid AND workplace_id = $workplaceid)"
            );

            // if not exists insert one
            if (count($relationship) == 0) {
                if ($this->model->addUserToWorkplace($workplaceid,$userid)) {
                    redirect('/user_workplace', ['message' => 'Sikeresen felvetted a munkahelyet!']);
                } else {
                    redirect('/user_workplace', ['message' => 'Sikertelen felvétel! Adatbázis hiba!']);
                }
            } else {
                redirect('/user_workplace', ['message' => 'Már felvetted a munkahelyet...']);
            }
        }
    }

}