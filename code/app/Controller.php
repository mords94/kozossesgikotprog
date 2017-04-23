<?php


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

        return view("home", [
            'login_message' => 'Sikeres kijelentkezés!',
        ]);
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
            'lastname'  => $request->post('email'),
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

        redirect('/friends');
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

    public function friends(Request $request)
    {
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
     * ACTION: /newclub
     * VERB: GET
     *
     * @param Request
     * @return String
     */
    public function newclub(Request $request)
    {
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

}