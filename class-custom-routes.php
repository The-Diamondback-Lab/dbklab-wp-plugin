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
            $server->register_route('mmidbklab/v1', '/featured_story', [
                'methods' => 'GET',
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

            $server->register_route('mmidbklab/v1', '/foobar', [
                'methods' => 'GET',
                'callback' => function () {
                    return array_keys($GLOBALS);
                },
            ]);
        });
    }
}