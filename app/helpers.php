<?php

namespace {
    if (!defined('LARAVEL_FRAMEWORK_5')) {
        define('LARAVEL_FRAMEWORK_5', true);
    }

    function url_md($name, $parameters = [], $absolute = true) {
        if (!strpos($name, '@')) {
            $name .= '@getIndex';
        }
        return action($name, $parameters, $absolute);
    }
    
    function getDomainUrl(){
        $pu = parse_url($_SERVER['REQUEST_URI']);
        return $pu["scheme"] . "://" . $pu["host"];
    }

    function queryLog() {
        global $queryLog;
        $time = 0;
        foreach ($queryLog as $query) {
            $time += $query->time;
        }
        vd($time);
        vde($queryLog);
    }

    function clearQueryLog() {
        global $queryLog;
        $queryLog = array();
    }

    // if (!function_exists('decryptWithPrivate')) {

        function decryptWithPrivate($text) {
            
            $private = '-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCdD9AUJ4KR247v
kg0fK7a1o3nlE+Uc4OovtaAtG4yKof1nk+1svvXkkRhu13bLmI8AdSvV/5KaMquS
q3BNScSrws72ANZyL7caIY3uncBaS5XaBxvzVgMLulz4ShcZEB+cQkCZrKRvwkVP
0mKAMVfxm1GPo2q0mprcEUQjMD8kVB7LpvNffKs2Ta4r08hrdny0+3Qn5t2x3Xjb
ASYU0Gyg4/nPPU5jjjFZ/uoRExvS8mvyT2PmsWHXPXXBmpjyrhJNJRx4pOVtN0U6
RJLburdEKqRRxTRa/uyqSQyl8Eq/QRU0yFutTG1UB1Fzy3yZ5A3ow0GOKOwvrKv0
GlTczdv9AgMBAAECggEAVDKO7M8FeyXFqX6VVDl9+D/L79dLgsC8KplmZegX3pXa
n/U4WYzBiyeRfpI5WAnP14H43v7kW4+AVN9dE8HREfccNdrbG1mjAfos+VdOL9nH
WptnC3r3pQjiICSv3zq6h20o7nkTqenueE0jiu9o4tfN0H/dizY6gHEtIuQRZWpw
FclBnFQM4CGNwCX3C33na5oxJU4E47NFC/VSINyWn7hMtU2fkgN/pSf+ARe/obhY
pNb9DyzBGTTn041VvlJhJclfjKIgZpQjQbzkWJXQ18ZI3G3612X5leqzl7B/WVD2
GM5OGeBzyjoD+7rGRq/b+2GxwVouu9iBleIRqraFjQKBgQDMDA5sNV4KqWOlsJ2p
jhyhhYN6DlsNji8Y2kkV8HMFYe837nzuvcqxxZyd3tMVIAccPHz8gxOARLa0qNBG
oBK/OZWKS/CL0n4yx4JBpgSlOIIVUYi2T9lsDIs/QlsvHSZlNBj/QIWSuZnqQiUW
uB/OHGlWGPf497LrAdN6W3qzowKBgQDFDTeqE+BJ8M2Skl7K/hrIt64JvZD0ujiY
p4khI+4Ccrw10V/Tv1xo+qdspnGoPUUb1eosaQR3ls+VZVH9Em7K7rOQeDIsund6
tK/lZrDG/UJaJz/5Lyw6Yadvn0nFg6iZJ1KpYJjKXR6Npa0dvcFYIjvCcPGOWZ+U
NC/CIDUr3wKBgGHObaNkuV028LLdQ9WgbwzlaK46715CB0VMbLf9d8TeusH+qRfv
FZe892OdCWUY4w+f1vFbCx/yz/ks6hjQZewPeCTAXd9H2IISq9c38wYXXhNF79gj
0j4+jQiXkAm3WU4teMXxcZVxLdviNND9FRHBAKTqdwJ6COMpDuuZqektAoGAeMDF
yMrPWmBfiZ96yYh4H2I4G634Q5BOmHWqWg2rkAKBhbVdtOQCnC5mJPXGlqOdUxZ+
n2Jno2Veph1l5eDC1kb68OBHVxOZni9Vzw1j7T1LyPVehbQ7tTccpRGG4qvwyJHJ
XRRd7TytOX46nQwojHrGBjyyOlp/qRFl5t9X1h8CgYASBZWWptLLYUwLwmbagF3r
7/iV7u9qgs/MUW/TKX5GNk2IF1VolsuncjR9WejX4j64MqvezKUJDXfauOZipTJv
DSF+8YkSsDp8q2OfgFrleDwm+ErekUZ52Gdp/JmF+Z6QE6TsdKl9jGT08lyB/Cml
etVBSppLCTboRQaET99ZTg==
-----END PRIVATE KEY-----';

            $tcrypt = new \Monkey\Crypt\TCrypt();
            $tcrypt->setPrivateKey($private);
            $decrypted = $tcrypt->decryptPhrase($text);
            return $decrypted;
        }
    // }

    if (!function_exists('is_iterable')) {
        function is_iterable($var) {
            return (is_array($var) || $var instanceof Traversable);
        }
    }
}

namespace Monkey {

    function action($name, $parameters = [], $absolute = true) {
        if (!strpos($name, '@')) {
            $name .= '@getIndex';
        }
        return \action($name, $parameters, $absolute);
    }
}



