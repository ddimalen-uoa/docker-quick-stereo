<?php
// mysql_compat.php — PHP 5.6-safe shim mapping mysql_* to mysqli_*
// Include this BEFORE any code that calls mysql_* functions.
$GLOBALS['__MYSQL_COMPAT_LAST_ERROR'] = null;
$GLOBALS['__MYSQL_COMPAT_LAST_ERRNO'] = null;

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
        $link = _mysql_compat_link($link);           // your helper to pick a default link
        if (!$link) {
            $GLOBALS['__MYSQL_COMPAT_LAST_ERROR'] = 'No MySQLi link available';
            $GLOBALS['__MYSQL_COMPAT_LAST_ERRNO'] = 2006; // "server has gone away" as a generic code
            error_log("[mysql_compat] No link for query: $query");
            return false;
        }

        $res = mysqli_query($link, $query);
        if ($res === false) {
            $GLOBALS['__MYSQL_COMPAT_LAST_ERROR'] = mysqli_error($link);
            $GLOBALS['__MYSQL_COMPAT_LAST_ERRNO'] = mysqli_errno($link);
            error_log("[mysql_compat] Query failed ({$GLOBALS['__MYSQL_COMPAT_LAST_ERRNO']}): {$GLOBALS['__MYSQL_COMPAT_LAST_ERROR']} | SQL: $query");
        } else {
            $GLOBALS['__MYSQL_COMPAT_LAST_ERROR'] = null;
            $GLOBALS['__MYSQL_COMPAT_LAST_ERRNO'] = null;
        }
        return $res;
    }

    function mysql_fetch_assoc($result) {
        if (!($result instanceof mysqli_result)) {
            return false;
        }
        return mysqli_fetch_assoc($result);
    }

    function mysql_fetch_row($result) {
        return @mysqli_fetch_row($result);
    }

    function mysql_fetch_array($result, $result_type = MYSQLI_BOTH) {
        if (!($result instanceof mysqli_result)) return false;
        return mysqli_fetch_array($result, $result_type);
    }

    function mysql_num_rows($result) {
        return ($result instanceof mysqli_result) ? mysqli_num_rows($result) : 0;
    }

    function mysql_real_escape_string($string, $link = null) {
        $link = _mysql_compat_link($link);
        return $link ? mysqli_real_escape_string($link, $string) : addslashes($string);
    }

    function mysql_insert_id($link = null) {
        $link = _mysql_compat_link($link);
        return $link ? mysqli_insert_id($link) : 0;
    }

    function mysql_error($link = null) {
        if ($link instanceof mysqli) return mysqli_error($link);
        return $GLOBALS['__MYSQL_COMPAT_LAST_ERROR'] ?? '';
    }

    function mysql_errno($link = null) {
        if ($link instanceof mysqli) return mysqli_errno($link);
        return $GLOBALS['__MYSQL_COMPAT_LAST_ERRNO'] ?? 0;
    }

    function mysql_affected_rows($link = null) {
        $link = _mysql_compat_link($link);
        return $link ? mysqli_affected_rows($link) : 0;
    }

    function mysql_close($link = null) {
        $link = _mysql_compat_link($link);
        return $link ? mysqli_close($link) : false;
    }

    function mysql_set_charset($charset, $link = null) {
        $link = _mysql_compat_link($link);
        return $link ? @mysqli_set_charset($link, $charset) : false;
    }

    function mysql_free_result($result) {
        if ($result) @mysqli_free_result($result);
    }
}