<?php
// mysql_compat.php — PHP 5.6-safe shim mapping mysql_* to mysqli_*
// Include this BEFORE any code that calls mysql_* functions.

if (!function_exists('mysql_connect') && function_exists('mysqli_connect')) {
    // Define old constants if missing (some code uses these)
    if (!defined('MYSQL_ASSOC')) define('MYSQL_ASSOC', 1);
    if (!defined('MYSQL_NUM'))   define('MYSQL_NUM',   2);
    if (!defined('MYSQL_BOTH'))  define('MYSQL_BOTH',  3);

    $GLOBALS['__MYSQLI_LINK'] = null;

    function _mysql_compat_link($link) {
        if ($link) return $link;
        if (isset($GLOBALS['__MYSQLI_LINK']) && $GLOBALS['__MYSQLI_LINK']) return $GLOBALS['__MYSQLI_LINK'];
        return null;
    }

    function mysql_connect($host, $user, $pass) {
        $link = @mysqli_connect($host, $user, $pass);
        if ($link) $GLOBALS['__MYSQLI_LINK'] = $link;
        return $link;
    }

    function mysql_select_db($db, $link = null) {
        $link = _mysql_compat_link($link);
        return $link ? @mysqli_select_db($link, $db) : false;
    }

    function mysql_query($query, $link = null) {
        $link = _mysql_compat_link($link);
        return $link ? @mysqli_query($link, $query) : false;
    }

    function mysql_fetch_assoc($result) {
        return @mysqli_fetch_assoc($result);
    }

    function mysql_fetch_row($result) {
        return @mysqli_fetch_row($result);
    }

    function mysql_fetch_array($result, $result_type = MYSQL_BOTH) {
        $type = MYSQLI_BOTH;
        if ($result_type === MYSQL_ASSOC) $type = MYSQLI_ASSOC;
        elseif ($result_type === MYSQL_NUM) $type = MYSQLI_NUM;
        return @mysqli_fetch_array($result, $type);
    }

    function mysql_num_rows($result) {
        return @mysqli_num_rows($result);
    }

    function mysql_real_escape_string($str, $link = null) {
        $link = _mysql_compat_link($link);
        return $link ? mysqli_real_escape_string($link, $str) : $str;
    }

    function mysql_insert_id($link = null) {
        $link = _mysql_compat_link($link);
        return $link ? mysqli_insert_id($link) : 0;
    }

    function mysql_error($link = null) {
        $link = _mysql_compat_link($link);
        return $link ? mysqli_error($link) : '';
    }

    function mysql_close($link = null) {
        $link = _mysql_compat_link($link);
        $GLOBALS['__MYSQLI_LINK'] = null;
        return $link ? @mysqli_close($link) : false;
    }

    function mysql_set_charset($charset, $link = null) {
        $link = _mysql_compat_link($link);
        return $link ? @mysqli_set_charset($link, $charset) : false;
    }

    function mysql_free_result($result) {
        if ($result) @mysqli_free_result($result);
    }
}