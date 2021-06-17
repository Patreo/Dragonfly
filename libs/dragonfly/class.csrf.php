<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

/**
 * Csrf - Cross Site Request Forgery
 * @category  Security
 */
class Csrf
{
    public static $token = null;

    function __construct()
    {
        $token = get_session('csrf_token');
        if (empty($token)) {
            $token = md5(random_str(12));
            set_session('csrf_token', $token);        //set new token in session if not available
        }
        self::$token = $token;
    }

    /**
     *  Csrf - Verify if the request is coming from our origin
     * @category  Security
     */
    public static function cross_check()
    {
        $current_token = get_session('csrf_token');

        $req_token = "";
        if (!empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            $req_token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        } elseif (!empty($_REQUEST['csrf_token'])) {
            $req_token = $_REQUEST['csrf_token'];
        }

        if ($req_token != $current_token) {
            render_error("Cross-site request forgery detected. Please contact the system administrator for more information", 403);
            exit;
        }

        return null;
    }
}
