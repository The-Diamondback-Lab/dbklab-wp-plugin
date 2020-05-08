<?php

class DBK_Custom_Routes
{
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    private function __construct()
    {
        $this->register_routes();
    }

    public function register_routes()
    {
        $this->register_featured_story();
    }

    public function register_featured_story()
    {
        add_action('rest_api_init', function ($server) {
            $namespace = 'mmidbklab/v1';

            $server->register_route($namespace, '/featured_story', [
                'methods' => WP_REST_Server::READABLE,
                'callback' => function () {
                    $query = new WP_Query([
                        'post_type' => 'post',
                        'meta_key' => 'featured-story',
                        'meta_value' => '1',
                        'orderby' => 'modified',
                        'order' => 'DESC',
                    ]);

                    // If there is such a post, return it. Otherwise return an empty object
                    if ($query->have_posts()) {
                        return get_post($query->posts[0]);
                    } else {
                        return new WP_Error(
                            'no_featured_story',
                            'No featured story',
                            ['status' => 404]
                        );
                    }
                },
            ]);

            $server->register_route($namespace, '/banner_article', [
                'methods' => WP_REST_Server::READABLE,
                'callback' => function () {
                    // If we're not showing banner articles, don't bother searching for one.

                    $cgv = $GLOBALS['cgv'];
                    // Fail-fast
                    if (!isset($cgv)) {
                        return new WP_ERROR(
                            'undefined_cgv',
                            'Is the "Custom Global Variable" plugin installed and activated?'
                        );
                    }

                    $show_banner_article = $GLOBALS['cgv']['show_banner_article'];
                    // Fail-fast
                    if (!isset($show_banner_article) or strtolower($show_banner_article) !== 'true') {
                        return new WP_ERROR(
                            'bad_cgv_key',
                            'CGV key "show_banner_article" is not equal to "true" (not case sensitive)'
                        );
                    }

                    $query = new WP_Query([
                        'post_type' => 'post',
                        'meta_key' => 'banner-article',
                        'meta_value' => '1',
                        'orderby' => 'modified',
                        'order' => 'DESC',
                    ]);

                    // If there is such a post, return it. Otherwise return an empty object
                    if ($query->have_posts()) {
                        return get_post($query->posts[0]);
                    } else {
                        return new WP_Error(
                            'no_banner_article',
                            'No banner article',
                            ['status' => 404]
                        );
                    }
                },
            ]);
        });
    }
}
