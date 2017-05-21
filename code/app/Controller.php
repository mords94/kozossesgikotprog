<?php
use Carbon\Carbon;


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
 *
 *
 * FONTOS:
 * Fényképek feltöltése, kommentelése
 * A profilképem fül hasonló a profilomhoz, ott lehet szerkeszteni, kommentelni
 * Klubokon belül két gomb: klubba belépés vagy elhagyés és saját klubjaim, továbbá új klubok alapítása
 * üzenetek küldése ? nem biztos
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
        $this->model->fixSequences();
        $data = [];
        if (auth()) {
            $friendRequests = $this->model->getFriendRequests(Auth::user()['id']);
            $data['friendRequests'] = $friendRequests;
        }

        return view('home', $data);
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

        if (strlen($request->post('password')) < 5 || strlen($request->post('password')) > 20) {
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

        foreach ($user as $attr) {
            if (empty($attr)) {
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

        foreach ($allSchool as $index => $school) {

            $selected = false;
            foreach ($schools as $userschool) {
                if ($userschool['id'] == $school['id']) {
                    $selected = true;
                }
            }

            $allSchool[$index]['selected'] = $selected;
        }

        $allWorkplace = $this->model->getAllWorkplaces();
        $workplaces = $this->model->getWorkplaces($user['id']);

        foreach ($allWorkplace as $index => $workplace) {

            $selected = false;
            foreach ($workplaces as $userworkplace) {
                if ($userworkplace['id'] == $workplace['id']) {
                    $selected = true;
                }
            }

            $allWorkplace[$index]['selected'] = $selected;
        }

        $message = $request->has('message') ? $request->get('message') : '';

        return view('form/profile',
            [
                'user'       => $user,
                "schools"    => $allSchool,
                'workplaces' => $allWorkplace,
                'message'    => $message,
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
        if (!$user) {
            return view('inc/error', [
                'message' => 'Ezzel az azonositoval nincs felhasználó.',
            ]);
        }
        $allSchool = $this->model->getAllSchools();
        $schools = $this->model->getSchools($user['id']);

        foreach ($allSchool as $index => $school) {

            $selected = false;
            foreach ($schools as $userschool) {
                if ($userschool['id'] == $school['id']) {
                    $selected = true;
                }
            }

            $allSchool[$index]['selected'] = $selected;
        }

        $allWorkplace = $this->model->getAllWorkplaces();
        $workplaces = $this->model->getWorkplaces($user['id']);

        foreach ($allWorkplace as $index => $workplace) {

            $selected = false;
            foreach ($workplaces as $userworkplace) {
                if ($userworkplace['id'] == $workplace['id']) {
                    $selected = true;
                }
            }

            $allWorkplace[$index]['selected'] = $selected;
        }

        $message = $request->has('message') ? $request->get('message') : '';

        $myFriends = $this->model->getFriends(Auth::user()['id']);
        $known = false;
        foreach ($myFriends as $friend) {
            if ($friend['id'] == $user['id']) {
                $known = true;
                break;
            }
        }

        $requestSent = false;
        $friendRequests = $this->model->getFriendRequests($user['id']);
        foreach ($friendRequests as $friend) {
            if ($friend['id'] == Auth::user()['id']) {
                $requestSent = true;
                break;
            }
        }

        $requestGot = false;
        $friendRequestsBack = $this->model->getFriendRequests(Auth::user()['id']);
        foreach ($friendRequestsBack as $friend) {
            if ($friend['id'] == $user['id']) {
                $requestGot = true;
                break;
            }
        }


        if ($user['photo_id'] != null) {
            $photo = $this->model->getPhoto($user['photo_id']);
        } else {
            $photo = ['title' => 'Default image', 'src' => '/assets/images/user.png'];
        }



        return view('profile',
            [
                'photo'       => $photo,
                'user'        => $user,
                "schools"     => $allSchool,
                "workplaces"  => $allWorkplace,
                'message'     => $message,
                'known'       => $known,
                'requestSent' => $requestSent,
                'requestGot'  => $requestGot,
                'work'        => $request->has('work'),
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


        $schools = $request->post('schools');
        $workplaces = $request->post('workplaces');

        if (!$this->model->updateUser($userid, $data)) {
            return view('inc/error', [
                'message' => 'Adatbázis hiba. Nem sikerült frissíteni a felhasználót.',
            ]);
        }

        if (!$this->model->updateUserSchool($userid, $schools)) {
            return view('inc/error', [
                'message' => 'Adatbázis hiba. Nem sikerült frissíteni a felhasználó iskoláit.',
            ]);
        }

        if (!$this->model->updateUserWorkplace($userid, $workplaces)) {
            return view('inc/error', [
                'message' => 'Adatbázis hiba. Nem sikerült frissíteni a felhasználó munkahelyeit.',
            ]);
        }

        redirect('/ownprofile',
            [
                'message' => 'Sikerült elmenteni.',
            ]
        );
    }

    /**
     * Action /comment/{userid}
     *
     * @param Request $request
     * @return string|View
     */
    public function comment(Request $request) {
        if($request->has('id') && $request->has('hozzaszolas')) {
            $id = $request->get('id');
            $comment = [
                'description' => html_entity_decode($request->post('hozzaszolas')),
                'photo_id' => $request->post('photo'),
                'user_id' => Auth::user()['id'],
            ];
        } else {
            return "HIBA";
        }

        if(!empty($comment['description'])) {
            if($this->model->addComment($comment)) {
                redirect('/photo/'.$id);
            }
        } else {
            return "ÜRES";
        }

        redirect('/photo/'.$id);
    }

    /*
     * Action: /photo
     */
    public function photo(Request $request)
    {
        secure();

        $user = Auth::user();

        if ($request->has(0)) {
            $user = $this->model->user($request->get(0));
        } else {
            return "HIBA";
        }


        if(!$user['photo_id'])  redirect('/profile/'.$user['id']);
        $photo = $this->model->getPhoto($user['photo_id']);
            if(!$photo) {
                redirect('/profile/'.$user['id']);
            }
        $comments = $this->model->getCommentsByPhotoId($user['photo_id']);

        return view('photo',
            [
                'photo'    => $photo,
                'comments' => $comments,
                'user'     => $user,
            ]
        );
    }

    public function upload_picture(Request $request)
    {
        secure();

        $src = $request->post('photo');
        $userid = Auth::user()['id'];

        if (!$this->model->uploadPhoto($src, 'Jelölljetekkh!!')) {
            return "INTERNAL_ERROR";
        }
        $newimage = $this->model->getDatabase()->lastInsertId();
        if ($this->model->addProfilePicture($userid, $newimage)) {
            Auth::getInstance()->update('photo_id', $newimage);

            return "SUCCESS";
        } else {
            return "INTERNAL_ERROR";
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
    public function friends(Request $request)
    {
        secure();

        $message = '';

        if ($request->has('message')) {
            $message = $request->get('message');
        }

        $user = $request->has(0) ? $request->get(0) : Auth::user()['id'];


        $friends = $this->model->getFriends($user);

        foreach ($friends as $index => $friend) {
            if ($friend['photo_id'] != null) {
                $friends[$index]['photo'] = $this->model->getPhoto($friend['photo_id']);
            } else {
                $friends[$index]['photo'] = ['title' => 'Default image', 'src' => '/assets/images/user.png'];
            }
        }

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
     * @return string|View|void
     */
    public function addFriend(Request $request)
    {
        secure();
        $userid = Auth::user()['id'];

        if ($request->has('friend')) {
            $friendid = $request->post('friend');

            if ($userid == $friendid) {
                return view('inc/error', [
                    'message' => 'Magadat nem jelölheted meg...',
                ])->inc(false);
            }

            // check if relationship exists between the users
            $relationship = $this->model->getDatabase()->selectFromWhere(
                'user_friend',
                "(user_id = $userid AND friend_id = $friendid) OR (user_id = $friendid AND friend_id = $userid)"
                , '');

            // if not exists insert one
            if (count($relationship) == 0) {
                if (!$this->model->sendFriendRequest($userid, $friendid)) {
                    return view('inc/error', [
                        'message' => 'Sikertelen jelölés! Adatbázis hiba!',
                    ])->inc(false);
                } else {
                    redirect('/profile/' . $friendid);

                    return;
                }
            } else {
                return view('inc/error', [
                    'message' => 'Már barátok vagytok/Már barátnak jelölted.',
                ])->inc(false);
            }
        }
    }

    /**
     * ACTION: /deleteFriend
     * VERB: POST
     *
     * @param Request
     * @return string
     */
    public function deleteFriend(Request $request)
    {
        if (!$request->has('friend')) return "HTTP 422: BAD REQUEST. Hiba nincs friend posztolva.";
        $this->model->removeFriendRelation(Auth::user()['id'], $request->post('friend'));

        redirect('/profile/' . $request->post('friend'));
    }

    public function approve(Request $request)
    {
        secure();
        if ($request->has('friend')) {
            $this->model->approveFriendRequest(Auth::user()['id'], $request->post('friend'));
        }

        redirect('/home');
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
        foreach ($friends as $friend) {
            $friendIds[] = $friend['id'];
        }
        $schoolIds = [];
        foreach ($schools as $school) {
            $schoolIds[] = $school['id'];
        }

        $workIds = [];
        foreach ($workplaces as $work) {
            $workIds[] = $work['id'];
        }

        $byschool = [];

        $bywork = [];

        if (!empty($schoolIds)) {
            $byschool = $this->model->recommendFriendBasedOnSchool($userid, $friendIds, $schoolIds);
            foreach ($byschool as $index => $friend) {
                $byschool[$index]['known'] = services()->userKnownRelationShip($userid, $friend['id']);
                $byschool[$index]['pending'] = services()->userPendingRelationShip($userid, $friend['id']);
                if ($friend['photo_id'] != null) {
                    $byschool[$index]['photo'] = $this->model->getPhoto($friend['photo_id']);
                } else {
                    $byschool[$index]['photo'] = ['title' => 'Default image', 'src' => '/assets/images/user.png'];
                }
            }

        }
        if (!empty($workIds)) {
            $bywork = $this->model->recommendFriendBasedOnWorkplace($userid, $friendIds, $workIds);
            foreach ($bywork as $index => $friend) {
                $bywork[$index]['known'] = services()->userKnownRelationShip($userid, $friend['id']);
                $bywork[$index]['pending'] = services()->userPendingRelationShip($userid, $friend['id']);
                if ($friend['photo_id'] != null) {
                    $bywork[$index]['photo'] = $this->model->getPhoto($friend['photo_id']);
                } else {
                    $bywork[$index]['photo'] = ['title' => 'Default image', 'src' => '/assets/images/user.png'];
                }
            }
        }

        $all = $this->model->getAllUsers();

        foreach ($all as $index => $friend) {
            $all[$index]['known'] = services()->userKnownRelationShip($userid, $friend['id']);
            $all[$index]['pending'] = services()->userPendingRelationShip($userid, $friend['id']);
            if ($friend['photo_id'] != null) {
                $all[$index]['photo'] = $this->model->getPhoto($friend['photo_id']);
            } else {
                $all[$index]['photo'] = ['title' => 'Default image', 'src' => '/assets/images/user.png'];
            }
        }


        return view('recommend',
            [
                'bywork'   => $bywork,
                'byschool' => $byschool,
                'allusers' => $all,
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
            'clubs' => $clubs,
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

        if ($this->model->getClubsByName($club)) {
            return view('inc/error', [
                'message' => 'Van már ilyen nevű klub.',
            ]);
        }
        $result = $this->model->saveClub($club);

        if ($result) {
            redirect('/newclub');
        } else {
            return view('inc/error', [
                'message' => 'Nem sikerült elmenteni a klubot. Adatbázis hiba.',
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
            'schools' => $schools,
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

        if ($this->model->getSchoolByName($school)) {
            return view('inc/error', [
                'message' => 'Van már ilyen iskola.',
            ]);
        }
        $result = $this->model->saveSchool($school);

        if ($result) {
            redirect('/newschool');
        } else {
            return view('inc/error', [
                'message' => 'Nem sikerült elmenteni az iskolát. Adatbázis hiba.',
            ]);
        }
    }

    public function newworkplace(Request $request)
    {
        secure();

        $workplaces = $this->model->getAllWorkplaces();

        return view("form/workplace", [
            'workplaces' => $workplaces,
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

        if ($this->model->getWorkplaceByName($workplace)) {
            return view('inc/error', [
                'message' => 'Van már ilyen munkahely.',
            ]);
        }
        $result = $this->model->saveWorkplace($workplace);

        if ($result) {
            redirect('/newworkplace');
        } else {
            return view('inc/error', [
                'message' => 'Nem sikerült elmenteni a munkahelyet. Adatbázis hiba.',
            ]);
        }
    }

    public function delete_workplace(Request $request)
    {
        secure();

        $workplacesID = $request->get(0);
        $this->model->deleteWorkplace($workplacesID);

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
                if ($this->model->addMemberToClub($userid, $clubid)) {
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
                if ($this->model->addUserToSchool($schoolid, $userid)) {
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
                if ($this->model->addUserToWorkplace($workplaceid, $userid)) {
                    redirect('/user_workplace', ['message' => 'Sikeresen felvetted a munkahelyet!']);
                } else {
                    redirect('/user_workplace', ['message' => 'Sikertelen felvétel! Adatbázis hiba!']);
                }
            } else {
                redirect('/user_workplace', ['message' => 'Már felvetted a munkahelyet...']);
            }
        }
    }


    public function generateUsers(Request $request)
    {
        $faker = Faker\Factory::create();
        $count = $request->get(0) ? $request->get(0) : 1;
        for ($i = 0; $i < $count; $i++) {
            echo "INSERT INTO public.users (email, firstname, lastname, password, gender, birthdate, photo_id
              ) VALUES (
              '$faker->email', 
              '$faker->firstname', 
              '" . str_replace("'", '', $faker->lastname) . "', 
              '" . md5(12345678) . "', 
              '" . (int)$faker->boolean . "', 
              '" . $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = date_default_timezone_get())->format("Y-m-d") . "', 
              null);";
        }
    }

    public function generateWorkplaces(Request $request)
    {
        $faker = Faker\Factory::create();
        $count = $request->get(0) ? $request->get(0) : 1;
        for ($i = 0; $i < $count; $i++) {
            echo "INSERT INTO public.workplace (name) VALUES ('" . str_replace("'", "", $faker->company) . "');";
        }
    }

    public function assignUsersToWorks(Request $request)
    {
        $faker = Faker\Factory::create();


        $workplaces = $this->model->getAllWorkplaces();
        $users = $this->model->getAllUsers();

        foreach ($users as $user) {
            shuffle($workplaces);
            $rand = rand(1970, 2000);
            $date = Carbon::createFromDate($rand, rand(1, 12), rand(1, 28));
            $dateTo = Carbon::createFromDate($rand + rand(1, 10), rand(1, 12), rand(1, 28));

            echo "INSERT INTO public.user_work (\"from\", \"to\", \"user_id\", \"workplace_id\"
                  ) VALUES ( 
                  '" . $date->toDateString() . "',
                  '" . $dateTo->toDateString() . "',
                  '" . $user['id'] . "',
                  '" . $workplaces[0]['id'] . "');";
            echo "<br>";
        }
    }

    public function assignUsersToSchools(Request $request)
    {
        $faker = Faker\Factory::create();


        $schools = $this->model->getAllSchools();
        $users = $this->model->getAllUsers();

        foreach ($users as $user) {
            shuffle($schools);
            $rand = rand(1970, 2000);
            $date = Carbon::createFromDate($rand, rand(1, 12), rand(1, 28));
            $dateTo = Carbon::createFromDate($rand + rand(1, 10), rand(1, 12), rand(1, 28));

            echo "INSERT INTO public.user_school (\"from\", \"to\", \"user_id\", \"school_id\"
                  ) VALUES ( 
                  '" . $date->toDateString() . "',
                  '" . $dateTo->toDateString() . "',
                  '" . $user['id'] . "',
                  '" . $schools[0]['id'] . "');";
            echo "<br>";

        }
    }

}