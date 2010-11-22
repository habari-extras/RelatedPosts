<?php

/**
 * RelatedPosts Plugin - Show the related posts of the provided post.
 * Copy relatedposts.php to your themedir and style it.
 * 
 *
 * Usage:
 * <code>$theme->related_posts($post);</code>
 *
 */

class recent_posts extends Plugin
{
	private $config= array();
	private $default_options= array (
		'num_post' => '5',
		);

	/**
	 * Required plugin info() implementation provides info to Habari about this plugin.
	 */
	/**
	 * Plugin init action, executed when plugins are initialized.
	 */
	public function action_init()
	{
		$this->class_name= strtolower( get_class( $this ) );
		foreach ( $this->default_options as $name => $unused ) {
			$this->config[$name]= Options::get( $this->class_name . '__' . $name );
		}
		$this->add_template('related_posts', dirname(__FILE__) . '/relatedposts.php');
	}
	
	/**
	 * Sets the new 'hide_replies' option to '0' to mimic current, non-reply-hiding
	 * functionality.
	 **/
	public function action_plugin_activation( $file )
	{
		if(Plugins::id_from_file($file) == Plugins::id_from_file(__FILE__)) {
			if ( Options::get( 'related_posts__count' ) == null ) {
				Options::set( 'related_posts__count', 5 );
			}
			if ( Options::get( 'related_posts__header' ) == null ) {
				Options::set( 'related_posts__header', '<h2>Related Posts</h2>' );
			}
		}
	}

	/**
	 * Respond to the user selecting Configure on the plugin page
	 **/
	public function configure()
	{
		$ui = new FormUI( strtolower( get_class( $this ) ) );
		$ui->append( 'text', 'count', 'related_posts__count', _t( 'No. of posts to be shown' ) );
		$ui->append( 'text', 'header', 'related_posts__header', _t( 'Header for Related Posts list' ) );
		$ui->append( 'submit', 'save', _t('Save') );
		return $ui;
	}

	public function action_add_template_vars( $theme, $handler_vars)
	{
		if( !$theme->template_engine->assigned( 'rl_header' ) ) {
			$theme->assign('rl_header', Options::get( 'related_posts__header' ) );
		}

	}

	public function theme_related_posts( $theme, $post = null )
	{	
		// If we don't give it a post use $theme's
		if( $post === null ) {
			$post = $theme->post;
		}
		
		$theme->related_entry = array();

		if( $post instanceOf Post ) {
			$post_type = Post::type( 'entry' );
			$post_status = Post::status( 'published' );	

			if( count( $post->tags ) ) {
				$theme->related_entry = Posts::get( array( 'content_type' => Post::type( 'entry' ),
					'status' => Post::status( 'published' ),
					'limit' => Options::get( 'related_posts__count' ),
					'vocabulary' => array('any' => $post->tags ),
					'not:id' => $post->id ) );
			}
		}
		return $theme->fetch( 'related_posts' );
	}
}
?>
