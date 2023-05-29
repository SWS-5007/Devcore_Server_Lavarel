<?php

namespace App\Lib\Helpers;

use \Illuminate\Support\Facades\Session;

class Helpers
{

    /**
     * 
     * @return string
     */
    public static function displayAlert($bag='message')
    {
        if (Session::has($bag)) {
            $message = explode('|', Session::get($bag));
            $text = $message[0];
            $class = 'info';

            if (count($message) > 1) {
                $class = strtolower($message[0]);
                $text = $message[1];
            }

            switch ($class) {
                case 'error':
                    $title = __('Error');
                    break;
                case 'info':
                    $title = __('Information');
                    break;
                case 'success':
                    $title = __('Success');
                    break;
                case 'warning':
                    $title = __('Warning');
                    break;
            }

            return sprintf('<div style="display:none" id="globalMessage" data-type="%s" data-title="%s" class="alert alert-%s">%s</div>', $class, $title, $class, __($text));
        }
        return '';
    }

    /**
     * 
     * @param type $key
     * @return type
     */
    public static function getTrans($key)
    {
        return \Lang::get($key);
    }

    public static function getMenu($menuName, $user = null)
    {

        if (is_callable('makeMenu')) {
            return makeMenu($menuName, $user);
        }

        /*return Menu::make('app', function (Builder $menu) {


            $menu->add(self::getTrans('generics.menus.dashboard'), ['route' => 'dashboard'])->icon('th-large')->prependIcon();

            //menu empresas
            $menuEmpresas = $menu->add(self::getTrans('generics.menus.business'), "#")->data('permissions', ['business_access'])->icon('building')->prependIcon();

            //menu empleados
            $menuEmpleados = $menu->add(self::getTrans('generics.menus.employees'), "#")->data('permissions', ['employees_access'])->icon('group')->prependIcon();
            $menuEmpleados->add(self::getTrans('generics.menus.employees_categories'), ['route' => 'employees_categories.index'])->data('permissions', ['employees_categories_list'])->icon('tags')->prependIcon()->active('employees/categories/*');



            //seguridad

            $menuSecuridad = $menu->add(self::getTrans('generics.menus.security'), "#")->data('permissions', ['auth_users_list', 'auth_roles_list'])->icon('lock')->prependIcon();
            $menuSecuridad->add(self::getTrans('generics.menus.security_users'), ['route' => 'auth_users.index'])->data('permissions', ['auth_users_list'])->icon('user')->prependIcon()->active('users/*');
            $menuSecuridad->add(self::getTrans('generics.menus.security_roles'), ['route' => 'auth_roles.index'])->data('permissions', ['auth_roles_list'])->icon('users')->prependIcon()->active('roles/*');

            //menu mantenimiento
            $menuMantenimiento = $menu->add(self::getTrans('generics.menus.maintenance'), "#")->data('permissions', ['manteinance_access'])->icon('wrench')->prependIcon();
            $menuMantenimiento->add(self::getTrans('generics.menus.maintenance_cache'), "#")->data('permissions', ['manteinance_cache'])->icon('bars')->prependIcon();
            $menuMantenimiento->add(self::getTrans('generics.menus.maintenance_backup'), ['route' => 'maintenance.backup'])->data('permissions', ['manteinance_backup'])->icon('hdd-o')->prependIcon();
        })->filter(function ($item) {
            if ($item->data('permissions')) {
                return Auth::check() && Auth::user()->can($item->data('permissions')) ?: false;
            }
            return true;
        });*/
    }

    public static function displayMenu($user)
    {
        return self::getMenu($user)->asUl();
    }

    public static function replace_accent($str)
    {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
        return str_replace($a, $b, $str);
    }

    public static function toURI($str, $replace = array(), $delimiter = '_')
    {
        if (!empty($replace)) {
            $str = str_replace((array) $replace, ' ', $str);
        }

        $clean = self::replace_accent($str);
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    /**
     * Generate a querystring url for the application.
     *
     * Assumes that you want a URL with a querystring rather than route params
     * (which is what the default url() helper does)
     *
     * @param  string  $path
     * @param  mixed   $qs
     * @param  bool    $secure
     * @return string
     */
    public static function qsUrl($path = null, $qs = array(), $secure = null)
    {
        $url = app('url')->to($path, $secure);
        if (count($qs)) {

            foreach ($qs as $key => $value) {
                $qs[$key] = sprintf('%s=%s', $key, urlencode($value));
            }
            $url = sprintf('%s?%s', $url, implode('&', $qs));
        }
        return $url;
    }

    public static function replaceQs($newValues)
    {

        $currentQuery = request()->query();

        foreach ($newValues as $name => $value) {
            $newQuery = $currentQuery;
            if ($value == null) {
                unset($currentQuery[$name]);
                $newQuery = $currentQuery;
            } else {
                $newQuery = array_merge($currentQuery, array($name => $value));
            }
        }

        $ret = '';
        foreach ($newQuery as $key => $value) {
            $ret .= (($ret != '') ? '&' : '') . $key;
            if ($value != '') {
                $ret .= '=' . $value;
            }
        }
        return $ret;
    }


    public static function replaceCurrentQs($name, $value)
    {
        $currentQuery = request()->query();

        $newQuery = $currentQuery;
        if ($value == null) {
            unset($currentQuery[$name]);
            $newQuery = $currentQuery;
        } else {
            $newQuery = array_merge($currentQuery, array($name => $value));
        }


        $ret = '';
        foreach ($newQuery as $key => $value) {
            $ret .= (($ret != '') ? '&' : '') . $key;
            if ($value != '') {
                $ret .= '=' . $value;
            }
        }
        return $ret;
    }

    public static function tableHeader($title = 'No title', $orderParam = '', $defaultOrd = 'ASC')
    {
        $ret = '<th';
        $actionLink = '';
        if ($orderParam) {
            $currentQuery = request()->query();
            $currentOrderRaw = request(config('filters.params.order'));

            $currentDirection = starts_with($currentOrderRaw, '-') ? 'DESC' : 'ASC';

            $currentOrder = starts_with($currentOrderRaw, '-') ? substr($currentOrderRaw, 1) : $currentOrderRaw;


            $action = [
                config('filters.params.order') => (($defaultOrd == 'DESC') ? '-' : '') . $orderParam
            ];


            $class = "sorting ";

            if ($currentOrder == $orderParam) {
                $class .= " active sorting_" . strtolower($currentDirection) . " ";
                //invierto el orden de la acción
                $action = [
                    config('filters.params.order') => (($currentDirection == 'DESC') ? '' : '-') . $orderParam
                ];
            }

            $newQuery = array_merge($currentQuery, $action);
            $actionLink = '';
            foreach ($newQuery as $key => $value) {
                $actionLink .= (($actionLink != '') ? '&' : '') . $key;
                if ($value != '') {
                    $actionLink .= '=' . $value;
                }
            }

            $ret .= " class='$class' ";
            $ret .= " data-sort-param='$orderParam' data-default-sort-direction='$defaultOrd' ";
        }
        $ret .= '>';

        if ($orderParam) {
            $ret .= "<a class='sortlink' href='?$actionLink'>";
        }

        $ret .= $title;

        if ($orderParam) {
            $ret .= "</a>";
        }
        $ret .= '</th>';
        return $ret;
    }
}
