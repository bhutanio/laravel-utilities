<?php

// Laravel Aliases
/**
 * @return \Bhutanio\Laravel\Services\MetaDataService
 */
function meta()
{
    return app('metadata');
}

/**
 * @param $config array
 *
 * @return \Bhutanio\Laravel\Services\Guzzler
 */
function guzzler($config = [])
{
    return app('guzzler', $config);
}

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

/**
 * Generate directory path for saving files.
 *
 * @param $text
 *
 * @return string
 */
function leveled_dir($text)
{
    $dirs = str_split($text, 1);
    if (count($dirs) > 2) {
        return $dirs[0].DIRECTORY_SEPARATOR.$dirs[1].DIRECTORY_SEPARATOR.$dirs[2];
    }

    return '0'.DIRECTORY_SEPARATOR.'0'.DIRECTORY_SEPARATOR.'0';
}

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

/**
 * Validate IP Address.
 *
 * @param string $ip    IP address
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

    return (bool) filter_var($ip, FILTER_VALIDATE_IP, $which);
}

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

    return (bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
}

// View Helpers

/**
 * @param $errors
 * @param $key
 *
 * @return string
 */
function form_errors($errors, $key)
{
    if ($errors->has($key)) {
        return '<span class="help-block form-error">'.$errors->first($key).'</span>';
    }

    return '';
}
