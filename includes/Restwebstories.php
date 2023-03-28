<?php
namespace REST_WEBSTORIES\Includes;

defined('ABSPATH') || exit;

class Restwebstories
{

	public function __construct()
	{
		add_action('rest_api_init', array($this, 'register_routes'));
	}

	public function register_routes()
	{
		register_rest_route(
			'wp/v2',
			'/web-stories',
			array(
				'methods' => 'GET',
				'callback' => array($this, 'get_all_web_stories'),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'wp/v2',
			'/web-storie/(?P<id>\d+)/(?P<slug>[-\w]+)',
			array(
				'methods' => 'GET',
				'callback' => array($this, 'get_web_story_by_id_and_slug'),
				'permission_callback' => '__return_true',
				'args' => array(
					'id' => array(
						'validate_callback' => 'rest_validate_request_arg',
						'required' => true,
						'type' => 'integer'
					),
					'slug' => array(
						'validate_callback' => 'rest_validate_request_arg',
						'required' => true,
						'type' => 'string'
					)
				)
			)
		);
	}

	public function get_all_web_stories($request)
	{
		$query_args = array(
			'post_type' => 'web-story',
			'posts_per_page' => 10,
		);

		$web_stories = new \WP_Query($query_args);
		$response = array();

		if ($web_stories->have_posts()) {
			while ($web_stories->have_posts()) {

				$web_stories->the_post();

				$response[] = array(
					'title' => get_the_title(),
					'slug' => get_post_field('post_name'),
					'featuredImage' => array(
						'mediaItemUrl' => get_the_post_thumbnail_url(),
						'altText' => get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true),
						'description' => get_post(get_post_thumbnail_id())->post_excerpt,
						'mediaType' => wp_check_filetype(get_the_post_thumbnail_url())['ext'],
						'post' => get_post_thumbnail_id(),
						'sizes' => array(
							'thumbnail' => get_the_post_thumbnail_url(null, 'thumbnail'),
							'medium' => get_the_post_thumbnail_url(null, 'medium'),
							'large' => get_the_post_thumbnail_url(null, 'large'),
							'full' => get_the_post_thumbnail_url(null, 'full'),
						),
						'height' => get_post_thumbnail_id() ? wp_get_attachment_metadata(get_post_thumbnail_id())['height'] : '',
						'width' => get_post_thumbnail_id() ? wp_get_attachment_metadata(get_post_thumbnail_id())['width'] : '',
						'credit' => get_post_thumbnail_id() ? get_post_meta(get_post_thumbnail_id(), 'credit', true) : '',
						'creditUrl' => get_post_thumbnail_id() ? get_post_meta(get_post_thumbnail_id(), 'credit_url', true) : '',
					),
					'id' => get_the_ID(),
				);
			}
		}
		wp_reset_postdata();
		return $response;
	}

	public function get_web_story_by_id_and_slug($request)
	{
		$id = $request->get_param('id');
		$slug = $request->get_param('slug');

		$query_args = array(
			'post_type' => 'web-story',
			'p' => $id,
			'name' => $slug,
		);

		$web_story = new \WP_Query($query_args);
		$response = array();

		if ($web_story->have_posts()) {
			while ($web_story->have_posts()) {
				$web_story->the_post();
				$response[] = array(
					'title' => get_the_title(),
					'content' => get_the_content(),
					'featuredImage' => array(
						'mediaItemUrl' => get_the_post_thumbnail_url(),
						'altText' => get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true),
						'description' => get_post(get_post_thumbnail_id())->post_excerpt,
					),
				);
			}
		}

		wp_reset_postdata();

		return $response;
	}

}
