<?php

if (!function_exists('get_millisecond')) {
    /**
     * Gets the number of milliseconds of the current time
     *
     * @return float
     */
    function get_millisecond()
    {
        list($t1, $t2) = explode(' ', microtime());

        return (int)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }
}

if (!function_exists('get_url_query')) {
    /**
     * 将参数变为字符串
     *
     * @param $array_query
     *
     * @return string string 'm=content&c=index&a=lists&catid=6&area=0&author=0&h=0®ion=0&s=1&page=1' (length=73)
     */
    function get_url_query($array_query)
    {
        $tmp = array();
        foreach ($array_query as $k => $param) {
            $tmp[] = $k . '=' . $param;
        }
        $params = implode('&', $tmp);

        return $params;
    }
}

if (!function_exists('disk_path')) {
    /**
     * @param          $path
     * @param string $diskNo
     *
     * @return array
     * @throws Exception
     */
    function disk_path($path, $diskNo = 'disk_0')
    {
        $diskPaths = [
            'disk_0' => 'uploads/'
        ];
        if (!isset($diskPaths[$diskNo])) {
            throw new Exception($diskNo . ' 磁盘设置不存在');
        }
        $diskPrefix = $diskPaths[$diskNo];

        return [
            'db_path' => $diskPrefix . $path,
        ];
    }
}

if (!function_exists('split_url')) {
    function split_url($url)
    {
        return explode(':', $url);
    }
}

if (!function_exists('selected_or_not')) {
    function selected_or_not($from, $to)
    {
        if ($from === $to) {
            return 'selected';
        }

        return '';
    }
}

if (!function_exists('get_md5_random_str')) {
    /**
     * Gets a random MD5 string
     *
     * @return string
     */
    function get_md5_random_str()
    {
        return md5(time() . uniqid() . str_random(16));
    }
}

