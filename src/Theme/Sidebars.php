<?php
declare(strict_types=1);

namespace ItalyStrap\Theme;

use \ItalyStrap\Config\ConfigInterface as Config;
use ItalyStrap\Event\Subscriber_Interface;
use function ItalyStrap\HTML\open_tag;
use function ItalyStrap\HTML\close_tag;

/**
 * Class for registering sidebars in template
 * There are a standard sidebar and 4 footer dynamic sidebars
 * @package ItalyStrap\Theme
 */
class Sidebars implements Registrable, Subscriber_Interface {

	const NAME = 'name';
	const ID = 'id';
	const DESCRIPTION = 'description';
	const CLASS_NAME = 'class';
	const BEFORE_WIDGET = 'before_widget';
	const AFTER_WIDGET = 'after_widget';
	const BEFORE_TITLE = 'before_title';
	const AFTER_TITLE = 'after_title';

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return [
			// 'hook_name'							=> 'method_name',
			'widgets_init'			=> static::CALLBACK,
		];
	}

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * Init sidebars registration
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @inheritDoc
	 */
	public function register() {
		foreach ( $this->config as $sidebar ) {
			\register_sidebar( $this->defaultSidebarConfig( $sidebar ) );
		}
	}

	/**
	 * @param array $sidebar
	 * @return array
	 */
	private function defaultSidebarConfig( array $sidebar ) : array {

		$widget_context = $sidebar['id'] . '-widget';
		$title_context = $sidebar['id'] . '-title';

		$defaults = [
			self::NAME			=> '',
			self::ID			=> '',
			self::DESCRIPTION	=> '',
			self::CLASS_NAME	=> '',
			self::BEFORE_WIDGET	=> open_tag( $widget_context, 'div', ['id' => '%1$s', 'class' => 'widget %2$s'] ),
			self::AFTER_WIDGET	=> close_tag( $widget_context ),
			self::BEFORE_TITLE	=> open_tag( $title_context, 'h3', [ 'class' => 'widget-title' ] ),
			self::AFTER_TITLE	=> close_tag( $title_context ),
		];

		return \array_merge( $defaults, $sidebar );
	}
}
