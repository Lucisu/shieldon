<?php
/*
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Shieldon\Firewall\Firewall\Captcha;

use Shieldon\Firewall\Captcha\CaptchaInterface;

/*
 * The factory creates driver instances.
 */
class CaptchaFactory
{
    /**
     * Create a captcha instance.
     *
     * @param string $type    The driver's type string.
     * @param array  $setting The configuration of that driver.
     *
     * @return CaptchaInterface
     */
    public static function getInstance(string $type, array $setting): CaptchaInterface
    {
        $className = '\Shieldon\Firewall\Firewall\Captcha\Item' . self::getCamelCase($type);

        return $className::get($setting);
    }

    /**
     * Check whether a messenger is available or not.
     *
     * @param string $type    The messenger's ID string.
     * @param array  $setting The configuration of that messanger.
     *
     * @return bool
     */
    public static function check(string $type, array $setting): bool
    {
        if (empty($setting['enable'])) {
            return false;
        }

        // If the class doesn't exist.
        if (!file_exists(__DIR__ . '/' . self::getCamelCase($type) . '.php')) {
   //         return false;
        }

        return true;
    }

    /**
     * Covert string with dashes into camel-case string.
     *
     * @param string $string A string with dashes.
     *
     * @return string
     */
    public static function getCamelCase(string $string = '')
    {
        $str = explode('-', $string);
        $str = implode('', array_map(function($word) {
            return ucwords($word); 
        }, $str));

        return $str;
    }
}