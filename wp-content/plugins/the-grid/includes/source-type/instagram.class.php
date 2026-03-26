<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

class The_Grid_Instagram {
	
	/**
	* Instagram API Key
	*
	* @since 1.0.0
	* @access private
	*
	* @var integer
	*/
	private $api_key;
	
	/**
	* Instagram transient
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $transient_sec;
	
	/**
	* Grid data
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $grid_data;

	/**
	* Instagram call number
	*
	* @since 1.0.0
	* @access private
	*
	* @var integer
	*/
	private $call_nb;
	
	/**
	* Instagram usernames
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $usernames = array();
	
	/**
	* Instagram hashtags
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $hashtags  = array();
	
	/**
	* Instagram count
	*
	* @since 1.0.0
	* @access private
	*
	* @var integer
	*/
	private $count;
	
	/**
	* Instagram media items
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $media = array();
	
	/**
	* Instagram last media
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $last_media = array();
	
	
	/**
	* Initialize the class and set its properties.
	* @since 1.0.0
	*/
	public function __construct($grid_data = '') {
		
		$this->get_transient_expiration();
		$this->grid_data = $grid_data;
		
	}
	
	/**
	* Get Instagram transient expiration
	* @since: 1.0.0
	*/
	public function get_transient_expiration(){
		
		$this->transient_sec = apply_filters('tg_transient_instagram', 3600);
		
	}
	
	/**
	* Return array of data
	* @since 1.0.0
	*/
	public function get_grid_items() {

		global $tg_is_ajax;
		
		// Return if it load more on ajax.
		if ( $tg_is_ajax ) {
			return;
		}

		$this->get_data(
			'media',
			$this->grid_data['instagram_username'],
			$this->grid_data['instagram_hashtag'],
			$this->grid_data['item_number']
		);
		
		$this->grid_data['ajax_data'] = htmlspecialchars(json_encode($this->last_media), ENT_QUOTES, 'UTF-8');
		
		if ( empty( $this->media ) && ! $tg_is_ajax ) {
				
			$error_msg = __( 'No content was found for the current ursername(s) and/or hashtag(s).', 'tg-text-domain' );
			throw new Exception($error_msg);

		}

		return $this->media;

	}
	
	/**
	* Return array of grid data
	* @since: 1.0.0
	*/
	public function get_grid_data(){

		return $this->grid_data;
		
	}
	
	/**
	* Get instagram data
	* @since 1.0.0
	*/
	public function get_data($type, $usernames, $hashtags, $count) {
		
		// store Instagram data
		$this->usernames = preg_replace('/\s+/', '', $usernames);
		$this->hashtags  = preg_replace('/\s+/', '', $hashtags);
		$this->count     = $count <= 0 ? 10 : $count;
		$this->count     = $this->count > 12 ? 12 : $this->count;
		
		// get last media from ajax
		$last_media = isset($_POST['grid_ajax']) && !empty($_POST['grid_ajax']) ? $_POST['grid_ajax'] : array();
		$this->last_media = $last_media;

		// prepare Instagram data
		$this->get_users();
		$this->get_hashtags();
		
		// retrieve Instagram data
		if ($type == 'media') {
			$this->get_media();
		} else if ($type == 'user_info') {	
			if ( is_array($this->user_data) ) {
				return reset($this->user_data);
			} else {
				return;
			}
			
		}

		return $this->media;
		
	}
	
	/**
	* Get users info
	* @since 1.0.0
	*/
	public function get_users() {
	
		$this->usernames = array_filter(explode(',', $this->usernames));

		foreach ($this->usernames as $index => $username) {
			
			$user = $this->get_user( $username );
			
			if ( empty( $user) ) {
				continue;	
			}
			
			$this->usernames[ $index ] = $user->id;

		}

	}
	
	/**
	* Get user info
	* @since 1.0.0
	*/
	public function get_user( $user ) {
		
		if ( is_numeric( $user ) ) {
			$user = $this->get_user_by_id( $user );
		} else {
			$user = $this->get_user_by_name( $user );	
		}

		if ( empty( $user->id ) ) {
			return false;
		}
		
		$this->user_data[ $user->id ] = $user;
		
		return $user;

	}
	
	/**
	* Get user by name
	* @since 1.0.0
	*/
	public function get_user_by_name( $name ) {
		
		$response = $this->get_response( 'https://api.instacloud.io/?path=/v1/users/' . $name );

		if ( empty( $response->data ) ) {
			return false;
		}
		
		
		return $response->data;


	}
	
	/**
	* Get user by id
	* @since 1.0.0
	*/
	public function get_user_by_id( $id ) {
		
		$variables = json_encode(
			array(
				'id'    => strval( $id ),
				'first' => 1,
			)
		);

		$request = add_query_arg(
			urlencode_deep(
				array(
					'variables'  => $variables,
					'query_hash' => '2c5d4d8b70cad329c4a6ebe3abb6eedd',
				)
			),
			'https://www.instagram.com/graphql/query/'
		);

		$response = $this->get_response( $request, $variables );

		if ( empty( $response->data->user->edge_owner_to_timeline_media->edges ) ) {
			return false;
		}
		
		$media = reset( $response->data->user->edge_owner_to_timeline_media->edges );
		
		if ( ! isset( $media->node->owner ) ) {
			return false;
		}
		
		return $this->get_user_by_name( $media->node->owner->username );
		
	}
	
	/**
	* Get shortcode
	* @since 1.0.0
	*/
	public function get_shortcode( $shortcode ) {
		
		$variables = json_encode(
			array(
				'shortcode'             => $shortcode,
                'child_comment_count'   => 0,
                'fetch_comment_count'   => 0,
                'parent_comment_count'  => 0,
                'has_threaded_comments' => true,
			)
		);

		$request = add_query_arg(
			urlencode_deep(
				array(
					'variables'  => $variables,
					'query_hash' => '55a3c4bad29e4e20c20ff4cdfd80f5b4',
				)
			),
			'https://www.instagram.com/graphql/query/'
		);

		$response = $this->get_response( $request, $variables );

		if ( empty( $response->data->shortcode_media->owner ) ) {
			return false;
		}

		$user = $response->data->shortcode_media->owner;
		$user->profile_picture = $user->profile_pic_url;
		$this->user_data[ $user->id ] = $user;

		return $user;
		
	}
	
	/**
	* Get hashtags
	* @since 1.0.0
	*/
	public function get_hashtags() {
		
		$this->hashtags = str_replace( '#', '', $this->hashtags );
		$this->hashtags = array_filter(explode(',', $this->hashtags));
		$this->hashtags = array_map('trim',$this->hashtags);
		
	}	
	
	/**
	* Retrieve media data
	* @since 1.0.0
	*/
	public function get_media() {

		//$prev = -1;

		//while ( count( $this->media ) < $this->count && count( $this->media ) !== $prev ) {

			// store previous number of media.
			//$prev = count( $this->media );

			// retrieve Instagram data
			
			$this->get_user_media();
			$this->get_hashtag_media();

			// sort all data by date
			usort($this->media, function($a, $b) {
				return str_replace('@', '',$b['date']) - str_replace('@', '',$a['date']);
			});

			// return only the number of element set in grid settings
			$this->media = array_slice($this->media, 0, $this->count);

			// get the last media id (max_id)
			//$this->get_last_media();
			
		//}
	
	}
	
	
	/**
	* Retrieve user media
	* @since 1.0.0
	*/
	public function get_user_media() {

		foreach ($this->usernames as $username ) {
			
			if ( isset( $this->last_media[ $username ] ) ) {
				break;
			}
			
			$variables = json_encode(
				array(
					'id'    => strval( $username ),
					'first' => (int) $this->count,
				)
			);
			
			$request = add_query_arg(
				urlencode_deep(
					array(
						'variables'  => $variables,
						'query_hash' => '2c5d4d8b70cad329c4a6ebe3abb6eedd',
					)
				),
				'https://www.instagram.com/graphql/query/'
			);
			
			$response = $this->get_response( $request, $variables );

			if ( empty( $response->data->user->edge_owner_to_timeline_media->edges ) ) {
				continue;
			}
			
			$posts = $response->data->user->edge_owner_to_timeline_media->edges;
			$this->build_media_array( $posts, $this->user_data[ $username ]->username, true );
			
		}

	}
	
	/**
	* Retrieve hashtag media
	* @since 1.0.0
	*/
	public function get_hashtag_media() {
	
		foreach ( $this->hashtags as $hashtag ) {

			$variables = json_encode(
				array(
					'tag_name' => $hashtag,
					'first'    => (int) $this->count,
				)
			);
			
			$request = add_query_arg(
				urlencode_deep(
					array(
						'variables'  => $variables,
						'query_hash' => '174a5243287c5f3a7de741089750ab3b',
					)
				),
				'https://www.instagram.com/graphql/query/'
			);
			
			$response = $this->get_response( $request, $variables );

			if ( empty( $response->data->hashtag->edge_hashtag_to_media->edges ) ) {
				continue;
			}

			$posts = $response->data->hashtag->edge_hashtag_to_media->edges;
			$this->build_media_array( $posts, $hashtag );
			
		}
		
	
	}
	
	
	/**
	* Get url response (transient)
	* @since 1.0.0
	*/
	public function get_response( $url, $variables = '' ) {

		global $tg_is_ajax;
		
		$transient_name = 'tg_grid_' . md5( $url );
		
		if ($this->transient_sec > 0 && ($transient = get_transient($transient_name)) !== false) {
			$response = $transient;
		} else {
			
			$response = wp_remote_get(
				$url,
				array(
					'timeout'   => 60,
					'sslverify' => false,
					'headers'   => array(
						'X-Csrftoken'      => '',
						'X-Requested-With' => 'XMLHttpRequest',
						'X-Instagram-Ajax' => '1',
						'X-Instagram-Gis'  => md5( $variables ),
						'User-Agent'       => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36',
						'Origin'           => 'https://www.instagram.com',
						'Referer'          => 'https://www.instagram.com',
						'Connection'       => 'close'
					),
					'cookies'  => array(
						'ig_or'      => 'landscape-primary',
						'ig_pr'      => '1',
						'ig_vh'      => 1080,
						'ig_vw'      => 1920,
						'ds_user_id' => 25025320
					)
				)
			);

			if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
				$error_msg  = __( 'Sorry, an error occured from Instagram API.' . $url, 'tg-text-domain' );
				throw new Exception($error_msg);
			}
			
			$response = json_decode( $response['body'] );

			if ( ! is_object( $response ) ) {
				$error_msg  = __( 'Sorry, an error occured from Instagram API.' . $url, 'tg-text-domain' );
				throw new Exception($error_msg);
			}
			
			set_transient($transient_name, $response, $this->transient_sec);

		}

		return $response;
		
	}

	/**
	* Store last media media
	* @since 1.0.0
	*/
	public function get_last_media() {
		
		// assign max id
		foreach ($this->media as $media => $data) {
			$this->last_media[$data['type']] = $data['ID'];
		}
		
	}
	
	/**
	* Get video
	* @since 1.0.0
	*/
	public function get_video( $node ) {
		
		if( empty( $node->is_video ) || empty( $node->video_url ) ){
			return '';
		}


		return array(
			'mp4' => $node->video_url,
		);

	}

	/**
	* Get excerpt
	* @since 2.1.0
	*/
	public function get_excerpt($data) {
	
		if ( ! isset( $data->edge_media_to_caption->edges[0]->node->text ) ){
			return;
		}
			
		$excerpt = $data->edge_media_to_caption->edges[0]->node->text;
		$excerpt = $excerpt ? preg_replace('~(\#)([^\s!,. /()"\'?]+)~', '<a href="https://www.instagram.com/explore/tags/$2/" target="_blank" class="tg-item-social-link">#$2</a>', $excerpt) : null;
		$excerpt = $excerpt ? preg_replace('~(\@)([^\s!,. /()"\'?]+)~', '<a href="https://www.instagram.com/$2/" target="_blank" class="tg-item-social-link">@$2</a>', $excerpt) : null;
		
		return $excerpt;
		
	}
	
	/**
	* Build data array for the grid
	* @since 1.0.0
	*/
	public function build_media_array($response, $type, $user = false) {

		foreach( $response as $node ) {

			$node = $node->node;
			
			if ( $user && isset( $this->user_data[$node->owner->id] ) ) {
				$user = $this->user_data[$node->owner->id];
			} elseif ( ! empty( $node->shortcode ) ) {
				$user = $this->get_shortcode( $node->shortcode );
			}

			$display_url = isset( $node->display_url ) ? $node->display_url : null;
			$display_url = ! $display_url && isset( $node->display_src ) ? $node->display_src : $display_url;
			$thumbnail_src = isset( $node->thumbnail_resources[4]->src ) ? $node->thumbnail_resources[4]->src : null;
			$thumbnail_src = ! $thumbnail_src && isset( $node->thumbnail_src ) ? $node->thumbnail_src : $thumbnail_src;
			
			
			$this->media[ $node->taken_at_timestamp ] = array(
				'ID'              => $node->id,
				'type'            => $type,
				'date'            => $node->taken_at_timestamp,
				'post_type'       => null,
				'format'          => ! empty( $node->video_url ) ? 'video' : null,
				'url'             => 'https://www.instagram.com/p/' . $node->shortcode,
				'url_target'      => '_blank',
				'title'           => null,
				'excerpt'         => $this->get_excerpt( $node ),
				'terms'           => null,
				'author'          => array(
					'ID'     => isset( $node->owner->id ) ? $node->owner->id : null,
					'name'   => isset( $user->username ) ? $user->username : '',
					'url'    => isset( $user->username ) ? 'https://www.instagram.com/'. $user->username .'/' : null,
					'avatar' => isset( $user->profile_picture ) ? $user->profile_picture : null,
				),
				'likes_number'    => isset( $node->edge_media_preview_like->count ) ? $node->edge_media_preview_like->count : null,
				'likes_title'     =>  __( 'Like on Instagram', 'tg-text-domain' ),
				'comments_number' => isset( $node->edge_media_to_comment->count ) ? $node->edge_media_to_comment->count : null,
				'views_number'    => null,
				'image'           => array(
					'alt'    => null,
					'url'    => $thumbnail_src,
					'lb_url' => $display_url ? $display_url : $thumbnail_src,
					'width'  => isset( $node->thumbnail_resources[4]->config_width ) ? $node->thumbnail_resources[4]->config_width : null,
					'height' => isset( $node->thumbnail_resources[4]->config_height ) ? $node->thumbnail_resources[4]->config_height : null,
				),
				'gallery'         => null,
				'video'           => array(
					'type'   => 'video',
					'source' => $this->get_video( $node ),
				),
				'audio'           => null,
				'quote'           => null,
				'link'            => null,
				'meta_data'       => null
			);
			
		}
		
	}
	
}
