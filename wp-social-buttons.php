<?php
/*
Plugin Name: Social Buttons
Plugin URI:  https://github.com/unikent/wp-social-buttons
Description: Add social sharing buttons to your site.
Version:     1.0
Author:      IS WebDev <is-webdev@kent.ac.uk>
License:     GPLv2
Text Domain: kent_wp
*/


wp_register_style('kent-social-buttons', plugins_url( 'kent-social-buttons.css' , __FILE__ ));

class KentSocialShare {

	public $services = array(
		'facebook' => array(
			'name' => 'Facebook',
			'icon' => 'facebook',
			'link' => 'http://www.facebook.com/sharer.php?u={url}&amp;t={title}'
		),
		'twitter' => array(
			'name' => 'Twitter',
			'icon' => 'twitter',
			'link' => 'http://twitter.com/home?status={title}%20{url}'
		),
		'google-plus' => array(
			'name' => 'Google Plus',
			'icon' => 'google-plus',
			'link' => 'https://plus.google.com/share?url={url}'
		),
		'linkedin' => array(
			'name' => 'Linked In',
			'icon' => 'linkedin',
			'link' => 'http://linkedin.com/shareArticle?mini=true&amp;url={url}&amp;title={title}'
		),
		'email' => array(
			'name' => 'Email',
			'icon' => 'email',
			'link' => 'mailto:content={url}&amp;title={title}'
		)
	);

	public function generateSocialLinks($url, $title){

		$html = '';
		foreach($this->services as $service){
			// Generate ShareLink
			$link = str_replace(array('{url}', '{title}'), array($url, $title), $service['link']);
			$icon =  $service['icon'];

			$html .= "<a href='{$link}' target='_blank' class='ksocial-{$icon}'><span class='sr-only'>Share via {$service['name']}</span></a>";
		}

		return '<div class="kent-social-links">'.$html.'</div>';
	}

}

function kent_addSocialShareIcons($html){
	global $post;

	if(!is_singular('post')) return $html;

	$sharing = get_option('kent_social_sharing');

	if(empty($sharing)){
		return $html;
	}

	$kSocialShare = new KentSocialShare();
	$markup = $kSocialShare->generateSocialLinks(get_permalink($post->ID), $post->post_title);

	switch($sharing){
		case "above":
			return $markup . $html;
			break;
		case "below":
			return $html . $markup;
			break;
		case "both":
			return $markup . $html . $markup;
			break;
	}

	return $html;
}

add_filter('the_content', 'kent_addSocialShareIcons');

function kent_add_social_scripts(){
	$sharing = get_option('kent_social_sharing');

	if(!empty($sharing)){
		wp_enqueue_style('kent-social-buttons');
	}
}
add_action('wp_enqueue_scripts', 'kent_add_social_scripts', 101);



function kent_register_social_buttons_customizer($wp_customize) {

	$wp_customize->add_section( 'social_config' , array(
		'title'      => __( 'Social Sharing', 'kent_wp' ),
		'priority'   => 170,
	) );

	$wp_customize->add_setting('kent_social_sharing', array(
		'type' => 'option'
	));

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'kent_social_sharing', array(
		'label'        => __( 'Social Sharing Buttons Location', 'kent_wp' ),
		'section'    => 'social_config',
		'settings'   => 'kent_social_sharing',
		'type'		=>'select',
		'choices'	=> array(
			'' => __('None','kent_wp'),
			'above' => __('Above Posts','kent_wp'),
			'below' => __('Below Posts','kent_wp'),
			'both'  => __('Above and below Posts','kent_wp')
		)
	) ) );

}
add_action('customize_register', 'kent_register_social_buttons_customizer');
