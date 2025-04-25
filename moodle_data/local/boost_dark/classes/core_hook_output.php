<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Class injector
 *
 * @package   local_boost_dark
 * @copyright 2024 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/boost_darkleft/gpl.html GNU GPL v3 or later
 */

namespace local_boost_dark;

/**
 * Class core_hook_output
 *
 * @package local_boost_dark
 */
class core_hook_output {

    /**
     * Function html_attributes
     *
     * @return array
     *
     * @throws \coding_exception
     */
    public static function html_attributes() {
        global $CFG;

        $theme = $CFG->theme;
        if (isset($_SESSION["SESSION"]->theme)) {
            $theme = $_SESSION["SESSION"]->theme;
        }

        // Native support.
        if ($theme == "boost_magnific" || $theme == "degrade") {
            return [];
        }

        // Upon request, I have removed support.
        if ($theme == "moove") {
            return [];
        }

        $return = [
            "data-basename" => $theme,
        ];

        $darkmode = "auto";
        if (isset($_COOKIE["darkmode"])) {
            $darkmode = $_COOKIE["darkmode"];
            $return["data-bs-theme"] = $darkmode;
        }

        if (!isguestuser()) {
            $darkmode = get_user_preferences("darkmode", $darkmode);
            $return["data-bs-theme"] = $darkmode;
        }
        if ($darkmode = optional_param("darkmode", false, PARAM_TEXT)) {
            $return["data-bs-theme"] = $darkmode;
        }
        return $return;
    }

    /**
     * Function before_html_attributes
     *
     * @param \core\hook\output\before_html_attributes $hook
     *
     * @return void
     *
     * @throws \coding_exception
     */
    public static function before_html_attributes(\core\hook\output\before_html_attributes $hook): void {
        $atributes = self::html_attributes();

        foreach ($atributes as $id => $value) {
            $hook->add_attribute($id, $value);
        }
    }

    /**
     * Function before_footer_html_generation
     *
     * @throws \dml_exception
     */
    public static function before_footer_html_generation() {
        $css = "
            <style>
                :root {
                    --bs-write:         " . self::get_config_default("bs-write", "#fff") . "
                    --bs-write-rgb:     " . self::get_config_default("bs-write-rgb", "255, 255, 255") . "
                    --bs-gray-100:      " . self::get_config_default("bs-gray-100", "#f8f9fa") . "
                    --bs-gray-100-rgb:  " . self::get_config_default("bs-gray-100-rgb", "248, 249, 250") . "
                    --bs-gray-200:      " . self::get_config_default("bs-gray-200", "#e9ecef") . "
                    --bs-gray-200-rgb:  " . self::get_config_default("bs-gray-200-rgb", "233, 236, 239") . "
                    --bs-gray-300:      " . self::get_config_default("bs-gray-300", "#dee2e6") . "
                    --bs-gray-300-rgb:  " . self::get_config_default("bs-gray-300-rgb", "222, 226, 230") . "
                    --bs-gray-400:      " . self::get_config_default("bs-gray-400", "#ced4da") . "
                    --bs-gray-400-rgb:  " . self::get_config_default("bs-gray-400-rgb", "206, 212, 218") . "
                    --bs-gray-500:      " . self::get_config_default("bs-gray-500", "#adb5bd") . "
                    --bs-gray-500-rgb:  " . self::get_config_default("bs-gray-500-rgb", "173, 181, 189") . "
                    --bs-gray-600:      " . self::get_config_default("bs-gray-600", "#6c757d") . "
                    --bs-gray-600-rgb:  " . self::get_config_default("bs-gray-600-rgb", "108, 117, 125") . "
                    --bs-gray-700:      " . self::get_config_default("bs-gray-700", "#495057") . "
                    --bs-gray-700-rgb:  " . self::get_config_default("bs-gray-700-rgb", "73, 80, 87") . "
                    --bs-gray-800:      " . self::get_config_default("bs-gray-800", "#393e4f") . "
                    --bs-gray-800-rgb:  " . self::get_config_default("bs-gray-800-rgb", "57, 62, 79") . "
                    --bs-gray-900:      " . self::get_config_default("bs-gray-900", "#2e3134") . "
                    --bs-gray-900-rgb:  " . self::get_config_default("bs-gray-900-rgb", "46, 49, 52") . "
                    --bs-gray-1000:     " . self::get_config_default("bs-gray-1000", "#1e1e25") . "
                    --bs-gray-1000-rgb: " . self::get_config_default("bs-gray-1000-rgb", "30, 30, 37") . "
                    --bs-gray-1100:     " . self::get_config_default("bs-gray-1100", "#0e0e11") . "
                    --bs-gray-1100-rgb: " . self::get_config_default("bs-gray-1100-rgb", "14, 14, 17") . "
                    --bs-black:         " . self::get_config_default("bs-black", "#000") . "
                    --bs-black-rgb:     " . self::get_config_default("bs-black-rgb", "0, 0, 0") . "

                    --bs-nav-drawer:           " . self::get_config_default("bs-nav-drawer", "#e8eaed") . "
                    --bs-nav-drawer-rgb:       " . self::get_config_default("bs-nav-drawer-rgb", "232, 234, 237") . "
                    --bs-link-color:           " . self::get_config_default("bs-link-color", "#98b6d9") . "
                    --bs-link-color-rgb:       " . self::get_config_default("bs-link-color-rgb", "152, 182, 217") . "
                    --bs-link-hover-color:     " . self::get_config_default("bs-link-hover-color", "#aacbf2") . "
                    --bs-link-hover-color-rgb: " . self::get_config_default("bs-link-hover-color-rgb", "170, 203, 242") . "
                    --bs-link-focus-color:     " . self::get_config_default("bs-link-focus-color", "#b3c0e8") . "
                    --bs-link-focus-color-rgb: " . self::get_config_default("bs-link-focus-color-rgb", "179, 192, 232") . "
                    --bs-text-color:           " . self::get_config_default("bs-text-color", "#cbd0d4") . "
                    --bs-text-color-rgb:       " . self::get_config_default("bs-text-color-rgb", "203, 208, 212") . "
                }
            </style>";
        $css = preg_replace('/\s+/', "", $css);
        echo $css;
    }

    /**
     * Function get_config_default
     *
     * @param $name
     * @param $default
     *
     * @return mixed|string
     * @throws \dml_exception
     */
    private static function get_config_default($name, $default) {
        $configname = str_replace("-rgb", "", $name);
        $configname = str_replace("-", "_", $configname);
        $config = get_config("local_boost_dark", $configname);

        if ($config === null || !is_string($config)) {
            return $default;
        }

        if (strpos($name, "rgb") !== false) {
            if (preg_match('/^#[0-9A-Fa-f]{6}$/', $config)) {
                $hex = ltrim($config, "#");
                return hexdec(
                        substr($hex, 0, 2)) . ', ' . hexdec(substr($hex, 2, 2)) . ', ' . hexdec(substr($hex, 4, 2)
                    ) . " !important;";
            }

            return $default;
        }

        return "{$config} !important;";
    }
}
