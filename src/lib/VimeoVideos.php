<?php

namespace Qscl\VimeoVideos;

class VimeoVideos
{
    const PLUGIN_SLUG = '46cl-vimeo-videos';

    public static function load()
    {
        add_action('admin_menu', function () {
            self::handleAdminFormSubmission();
            add_options_page(
                'Vimeo Videos', 'Vimeo Videos', 'manage_options', self::PLUGIN_SLUG,
                function () {
                    self::renderAdminMenu();
                }
            );
        });
    }

    public static function getToken()
    {
        return get_option('46cl_vimeo_token');
    }

    public static function getVideo($id)
    {
        if (is_null(static::getToken())) {
            return null;
        }

        $client = static::getHttpClient();
        $response = $client->get("/me/videos/" . $id);

        if ($response->getStatusCode() != 200) {
            // Video not found
            return null;
        }

        $video = json_decode($response->getBody());
        $files = array_map(function ($file) {
            return [
                'link' => $file->link,
                'quality' => $file->quality
            ];
        },
            array_filter($video->files, function ($file) {
                return $file->quality === 'hd' || $file->quality === 'hls';
            })
        );
        return [
            'id' => $id,
            'name' => $video->name,
            'files' => array_values($files)
        ];
    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Renders the admin template.
     */
    private static function renderAdminMenu()
    {
        $tpl_folder = __DIR__ . '/../templates/';
        // Provides a direct access to the Wordpress options for the plugin templates (`$o` for "option").
        $o = function ($option) {
            $value = get_option($option);
            if (!empty($value))
                echo $value;
        };
        // Renders a template with a limited scope containing the function `$o()`.
        $render = function ($tpl) use ($o) {
            include $tpl;
        };
        $render($tpl_folder . 'admin.tpl.php');
    }

    /**
     * Handles the submitted admin options.
     */
    private static function handleAdminFormSubmission()
    {
        if (!empty($_POST['option_page']) && $_POST['option_page'] == self::PLUGIN_SLUG) {
            foreach ($_POST as $name => $value) {
                if ($name == 'option_page')
                    continue;
                update_option($name, $value);
            }
            add_settings_error(
                self::PLUGIN_SLUG,
                '46_vimeo_videos_updated',
                __('Settings saved.'),
                'updated'
            );
        }
    }

    /**
     * Gets a HTTP client pre-configured for communicating with the Vimeo API
     */
    private static function getHttpClient()
    {
        return new GuzzleHttp\Client([
            'base_uri' => 'https://api.vimeo.com',
            'timeout' => 5.0,
            'headers' => [
                'User-Agent' => '46cl-WP-VimeoVideos/1.0',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . self::getToken()
            ]
        ]);
    }
}