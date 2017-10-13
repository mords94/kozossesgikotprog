<?php
namespace Library;

class Session extends Singleton {

    use PropertyTrait;

    public function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function id() {
        return session_id();
    }

    public function getAll() {
        return $_SESSION;
    }

    public function get($key)
    {
        if(isset($_SESSION[$key]))
        return $_SESSION[$key];
        else return "";
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;

        return $this;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        /*$_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }*/

        session_destroy();

    }
}

