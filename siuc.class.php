<?php

/**
 * @project siuc.biz
 * @author Artem Bondarenko
 *
 */


class Siuc
{

    protected $url = 'https://click.siuc.biz/?';
    protected $unic;
    protected $version = 19;
    protected $counter;

    public function __construct()
    {

        if (!defined('_SIUC_')) {
            return false;
        }
        $this->unic = _SIUC_;
        $this->visit();
        return $this;

    }



    private function visit()
    {
        if (preg_match('#getsuicversion#', $_SERVER['REQUEST_URI'])) {
            echo 'getsuicversion'.$this->version.'getsuicversion';

            exit();
        }


        if (isset($_COOKIE['utm_sount'])) {
            $cookie = $_COOKIE['utm_sount'];
        } else {
            $cookie = substr(uniqid(), 0, 16);
        }

        @setcookie('utm_sount', $cookie, time() + 3600 * 24 * 10);
        $params = array(
            'r' => isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'',
            'ip' => isset($_SERVER['HTTP_X_FORWARDED_FOR'])?@array_pop(@explode(',',$_SERVER['HTTP_X_FORWARDED_FOR'])):$_SERVER['REMOTE_ADDR'],
            'method' => 2,
            'cookie' => $cookie,
        );

        $s = curl_init($this->url . http_build_query($params));
        curl_setopt($s, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($s, CURLOPT_REFERER, 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($s, CURLOPT_TIMEOUT, 5);

        $this->counter = curl_exec($s);
        curl_close($s);
    }

    public function counter()
    {
        return $this->counter;
    }
}

