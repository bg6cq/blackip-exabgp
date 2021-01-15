<?php

# 2009-06-24  v0.1  Frank.Meisschaert@UGent.be
#
# UGCAS_Simple is a CAS client module with the same functionality as UGent
# Webauth: the username of the person is returned. No extra attributes nor
# logout is supported.
#
# The function "ugcas_simple_remote_user()" emulates basic authentication.
# For example:
#
#    require_once('UGCAS_Simple.php');
#    $user = ugcas_simple_remote_user();
#    echo "hello $user";
#
# This function will start a session with "session_start()" if no session 
# is active. So be sure to handle session configuration stuff before
# calling "ugcas_simple_remote_user()". Also this function must be called
# in every php script which is accessible from the outside, and no basic
# authentication may be configured unless of course you want to authenticate
# twice. ;-)
#
# Use of the "UGCAS_Simple" class is more flexible, but requires a bit
# more programming. You instantiate an "UGCAS_Simple" object with the url
# of the application. The "login_url" method returns the url for the
# initial CAS redirect. The "service_validate" method validates the ticket
# and returns the username. An example:
#
#    define('MY_URL','http://www.muziek.ugent.be/ugcas_simple_hello.php');
#
#    require_once('UGCAS_Simple.php');
#
#    session_start();
#
#    $user = $_SESSION['user'];
#
#    if (!$user) {
#        $cas = new UGCAS_Simple(MY_URL);
#        if ($ticket = $_GET['ticket']) {
#            # returning from CAS
#            $user = $cas->service_validate($ticket);
#            if ($user) {
#                $_SESSION['user'] = $user;
#            }
#            else {
#                echo "<h1>ERROR</h1>\n";
#                echo "<p>Authentication failed:\n";
#                echo "<p>" . $cas->error;
#                exit(0);
#            }
#        }
#        else {
#            # redirect to CAS
#            $login_url = $cas->login_url();
#            echo "<a href=\"$login_url\">login</a>";
#            exit(0);
#        }
#    }
#
#    echo "hello $user";
#

class UGCAS_Simple {
    var $cas_url = 'https://passport.ustc.edu.cn/';
    var $cas_ns = 'http://www.yale.edu/tp/cas';
    var $xml_user_index;
    var $xml_gid_index;
    var $xml_fail_index;

    var $app_url;
    var $service;
    var $user;
    var $gid;

    var $error;
    var $answer;

    function UGCAS_Simple($url) {
        $this->xml_user_index = $this->cas_ns . ':user';
        $this->xml_gid_index = $this->cas_ns . ':gid';
        $this->xml_fail_index = $this->cas_ns . ':authenticationFailure';

        $this->app_url = $url;
        $this->service = urlencode($this->app_url);
    }

    function login_url() {
        return $this->cas_url . 'login?service=' . $this->service;
    }
    function gid() {
        return $this->gid;
    }
    function user() {
        return $this->user;
    }

    function service_validate($ticket) {
        $validate_url = $this->cas_url . 'serviceValidate'
                      . '?service=' . $this->service
                      . '&ticket=' . $ticket
                      ;

        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$validate_url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        $text = curl_exec($curl);
        if (!$text) $this->error = curl_error();
        curl_close($curl);

        if (!$text) return NULL;
        $this->answer = $text;

        $parser = xml_parser_create_ns();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        $parse_ok = xml_parse_into_struct($parser, $text, $values, $index);
        if (!$parse_ok)
            $this->error = xml_error_string(xml_get_error_code($parser));
        xml_parser_free($parser);

        if (!$parse_ok) return NULL;

/*	echo "<pre>";
	var_dump($values);
	var_dump($index);
	echo "</pre>";
*/

        if ($gid_index = $index[$this->xml_gid_index][0]) {
            $this->gid = $values[$gid_index]['value'];
	}
        if ($user_index = $index[$this->xml_user_index][0]) {
            $this->user = $values[$user_index]['value'];
            return $this->user;
        }
        elseif ($fail_index = $index[$this->xml_fail_index][0]) {
            $this->error = $values[$fail_index]['value'];
            return false;
        }
        else {
            $this->error = 'authentication failed';
            return false;
        }
    }
}

function ustc_cas_login() {
    if (!session_id()) session_start() or exit("can't start session");

    $proto = $_SERVER['HTTPS'] ? 'https://' : 'http://';
    $default_port = $_SERVER['HTTPS'] ? 443 : 80;
    $port = $_SERVER['SERVER_PORT'];

    $request_uri = $_SERVER['REQUEST_URI'];
    if ($_GET['ticket']) {
        // recover url sent to cas
        function is_not_ticket($param) {
            return !preg_match('/^ticket=/',$param);
        }
        list($path,$query_string) = explode('?',$request_uri,2);
        $url_params = explode('&',$query_string);
        $url_params = array_filter($url_params,'is_not_ticket');
        $query_string = implode('&',$url_params);

        $request_uri = $path;
        if ($query_string) $request_uri .= '?' . $query_string;
    }

    $my_url = $proto . $_SERVER['SERVER_NAME'];
    if ($port != $default_port) $my_url .= ':' . $port;
    $my_url .= $request_uri;

    $cas = new UGCAS_Simple($my_url);
    if ($ticket = $_GET['ticket']) {
        $user = $cas->service_validate($ticket);
        if ($user) {
            $_SESSION['ustc_cas_user'] = $user;
            return $cas;
        }
        else {
            echo "<h1>ERROR</h1>\n";
            echo "<p>Authentication failed:\n";
            echo "<p>" . $cas->error;
            exit(0);
        }
    }
    else {
        $login_url = $cas->login_url();
        header('Location: ' . $login_url);
        exit(0);
    }
}

?>
