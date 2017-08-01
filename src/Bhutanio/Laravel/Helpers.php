<?php

if (!function_exists('user')) {
    /**
     * @return \Bhutanio\Laravel\Services\UserDataService
     */
    function user()
    {
        return app(\Bhutanio\Laravel\Services\UserDataService::class);
    }
}

if (!function_exists('meta')) {
    /**
     * @return \Bhutanio\Laravel\Services\MetaDataService
     */
    function meta()
    {
        return app(Bhutanio\Laravel\Services\MetaDataService::class);
    }
}

if (!function_exists('guzzler')) {
    /**
     * @param $config array
     *
     * @return \Bhutanio\Laravel\Services\Guzzler
     */
    function guzzler($config = [])
    {
        return app(Bhutanio\Laravel\Services\Guzzler::class, $config);
    }
}

if (!function_exists('carbon')) {
    /**
     * @param null $time
     * @param null $tz
     *
     * @return \Carbon\Carbon
     */
    function carbon($time = null, $tz = null)
    {
        return new \Carbon\Carbon($time, $tz);
    }
}

if (!function_exists('flash')) {
    function flash($message, $type = 'info')
    {
        if ($type == 'error') {
            $type = 'danger';
        }

        if (!in_array($type, ['success', 'info', 'warning', 'danger'])) {
            $type = 'info';
        }

        app('session')->flash('flash_message', [
            'type'    => $type,
            'message' => $message,
        ]);
    }
}

if (!function_exists('leveled_dir')) {
    /**
     * Generate directory path for saving files.
     *
     * @param $text
     *
     * @return string
     */
    function leveled_dir($text)
    {
        if (strlen($text) > 2) {
            $dirs = str_split($text, 1);
        } else {
            $dirs = str_split(md5($text), 1);
        }

        return $dirs[0] . DIRECTORY_SEPARATOR . $dirs[1] . DIRECTORY_SEPARATOR . $dirs[2];
    }
}

/********************************************************************************************
 * IP Helpers
 ********************************************************************************************/

if (!function_exists('get_ip')) {
    /**
     * Get IP Address, checks for cloudflare headers.
     *
     * @return string
     */
    function get_ip()
    {
        if (getenv('HTTP_CF_CONNECTING_IP') && is_valid_ip(getenv('HTTP_CF_CONNECTING_IP'))) {
            return getenv('HTTP_CF_CONNECTING_IP');
        }

        return request()->getClientIp();
    }
}


if (!function_exists('is_valid_ip')) {
    /**
     * Check if IP Address is a valid IP.
     *
     * @param string $ip IP address
     * @param string $which IP protocol: 'ipv4' or 'ipv6'
     *
     * @return bool
     */
    function is_valid_ip($ip, $which = 'ipv4')
    {
        if ($ip == '0.0.0.0' || $ip == '127.0.0.1') {
            return false;
        }
        switch (strtolower($which)) {
            case 'ipv4':
                $which = FILTER_FLAG_IPV4;
                break;
            case 'ipv6':
                $which = FILTER_FLAG_IPV6;
                break;
            default:
                $which = null;
                break;
        }

        return (bool)filter_var($ip, FILTER_VALIDATE_IP, $which);
    }
}

if (!function_exists('is_valid_ipv6')) {
    /**
     * Check if ip is a valid ipv6
     *
     * @param $ip
     * @return bool
     */
    function is_valid_ipv6($ip)
    {
        return is_valid_ip($ip, 'ipv6');
    }
}

if (!function_exists('is_public_ip')) {
    /**
     * Validate IPv4 Address (Check if it is a public IP).
     *
     * @param string $ip IP address
     *
     * @return bool
     */
    function is_public_ip($ip)
    {
        if (!is_valid_ip($ip)) {
            return false;
        }

        return (bool)filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}

/********************************************************************************************
 * View Helpers
 ********************************************************************************************/

if (!function_exists('form_error')) {
    /**
     * @param $errors \Illuminate\Support\ViewErrorBag
     * @param $field string
     * @return string
     */
    function form_error($errors, $field)
    {
        if ($errors->has($field)) {
            return '<span class="help-block"><strong>' . $errors->first($field) . '</strong></span>';
        }

        return '';
    }
}

if (!function_exists('form_error_class')) {
    /**
     * @param $errors \Illuminate\Support\ViewErrorBag
     * @param $field string
     * @return string
     */
    function form_error_class($errors, $field)
    {
        if ($errors->has($field)) {
            return ' has-error';
        }

        return '';
    }
}

if (!function_exists('form_select')) {
    /**
     * Returns an array to be used in Laravel's form builder.
     *
     * @param $data
     * @param $name
     * @param string $id
     * @return array
     */
    function form_select($data, $name, $id = 'id')
    {
        $new_data = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_data[$value[$id]] = $value[$name];
            } elseif (is_object($value)) {
                $new_data[$value->$id] = $value->$name;
            }
        }

        return $new_data;
    }
}

if (!function_exists('form_select_json')) {
    /**
     * Returns JSON of $data to be used in JavaScript tools like jQuery Plugins.
     *
     * @param        $data
     * @param        $name
     * @param string $id
     * @param bool $sort
     *
     * @return string
     */
    function form_select_json($data, $name, $id = 'id', $sort = true)
    {
        $selects = form_select($data, $id, $name, $sort);
        $result = [];
        foreach ($selects as $key => $value) {
            $result[] = [
                'id'   => $key,
                'text' => $value,
            ];
        }

        return json_encode($result);
    }
}

if (!function_exists('form_select_csv')) {
    /**
     * Returns a CSV string from $data (can be a Laravel Collection).
     *
     * @param      $datas
     * @param bool $column
     *
     * @return string
     */
    function form_select_csv($datas, $column = false)
    {
        $output = '';

        foreach ($datas as $data) {
            if ($column) {
                if (is_array($data)) {
                    $output .= $data[$column] . ',';
                } elseif (is_object($data)) {
                    $output .= $data->$column . ',';
                }
            } else {
                $output .= $data . ',';
            }
        }

        $output = rtrim($output, ',');

        return $output;
    }
}


if (!function_exists('order_sort')) {
    /**
     * @param        $name
     * @param        $order_column
     * @param        $order_by
     * @param string $default
     *
     * @return string
     */
    function order_sort($name, $order_column, $order_by, $default = 'desc')
    {
        if ($name == $order_column) {
            return ($order_by == 'desc') ? 'asc' : 'desc';
        }

        return $default;
    }
}

if (!function_exists('order_sort_icon')) {
    /**
     * Sort icon
     *
     * @param $name
     * @param $order_column
     * @param $order_by
     * @return string
     */
    function order_sort_icon($name, $order_column, $order_by)
    {
        if ($name == $order_column && $order_by == 'desc') {
            return '<i class="fa fa-fw fa-sort-desc"></i>';
        } elseif ($name == $order_column) {
            return '<i class="fa fa-fw fa-sort-asc"></i>';
        }

        return '<i class="fa fa-fw fa-sort"></i>';
    }
}

if (!function_exists('order_sort_row')) {
    /**
     * Sort Link
     *
     * @param $action_url
     * @param $order_appends
     * @param $order
     * @param $sort
     * @param $column
     * @param null $title
     * @param string $default_sort
     * @param null $display
     * @return string
     */
    function order_sort_row(
        $action_url,
        $order_appends,
        $order,
        $sort,
        $column,
        $title = null,
        $default_sort = 'desc',
        $display = null
    ) {
        return '<a href="' . action($action_url,
                $order_appends + ['order' => $column, 'sort' => order_sort($column, $order, $sort, $default_sort)])
            . '" class="sort" data-toggle="tooltip" title="Order by ' . ($title ? $title : title_case($column)) . '">' . ($display ? $display : ($title ? $title : title_case($column))) . order_sort_icon($column,
                $order, $sort) . "</a>";
    }
}
