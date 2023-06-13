<?php

namespace WPSpeedo_Team;

if ( ! defined( 'ABSPATH' ) ) exit;

class Group_Control_Background extends Group_Base_Control {

	protected static $fields;

	private static $background_types;

	public static function get_type() {
		return 'background';
	}

	public static function get_background_types() {
		if ( null === self::$background_types ) {
			self::$background_types = self::get_default_background_types();
		}
		return self::$background_types;
	}

	private static function get_default_background_types() {
		return [
			'classic' => [
				'title' => _x( 'Classic', 'Background Control', 'wpspeedo-team' ),
				'icon' => 'fas fa-paint-brush',
			],
			'gradient' => [
				'title' => _x( 'Gradient', 'Background Control', 'wpspeedo-team' ),
				'icon' => 'fas fa-palette',
			]
		];
	}

	public function init_fields() {
		$fields = [];

		$fields['type'] = [
			'label' => _x( 'Background Type', 'Background Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::CHOOSE,
			'separator' => '',
		];

		$fields['color'] = [
			'label' => _x( 'Color', 'Background Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::COLOR,
			'separator' => '',
			'default' => '',
			'title' => _x( 'Background Color', 'Background Control', 'wpspeedo-team' ),
			'condition' => [
				'type' => [ 'classic', 'gradient' ],
			],
		];

		$fields['color_stop'] = [
			'label' => _x( 'Location', 'Background Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SLIDER,
			'separator' => '',
			'unit' => '%',
			'default' => 0,
			'of_type' => 'gradient',
			'condition' => [
				'type' => [ 'gradient' ],
			],
		];

		$fields['color_b'] = [
			'label' => _x( 'Second Color', 'Background Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::COLOR,
			'separator' => '',
			'default' => '#f2295b',
			'condition' => [
				'type' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['color_b_stop'] = [
			'label' => _x( 'Location', 'Background Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SLIDER,
			'separator' => '',
			'unit' => '%',
			'default' => 100,
			'condition' => [
				'type' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_type'] = [
			'label' => _x( 'Type', 'Background Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SELECT,
			'separator' => '',
			'label_block' => true,
			'options' => [
				[ 'label' => _x( 'Linear', 'Background Control', 'wpspeedo-team' ), 'value' => 'linear' ],
				[ 'label' => _x( 'Radial', 'Background Control', 'wpspeedo-team' ), 'value' => 'radial' ],
			],
			'default' => 'linear',
			'condition' => [
				'type' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_angle'] = [
			'label' => _x( 'Angle', 'Background Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SLIDER,
			'separator' => '',
			'unit' => 'deg',
			'max' => 360,
			'default' => 180,
			'step' => 5,
			'condition' => [
				'type' => [ 'gradient' ],
				'gradient_type' => 'linear',
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_position'] = [
			'label' => _x( 'Position', 'Background Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SELECT,
			'separator' => '',
			'label_block' => true,
			'options' => [
				[ 'label' => _x( 'Center Center', 'Background Control', 'wpspeedo-team' ), 'value' => 'center center' ],
				[ 'label' => _x( 'Center Left', 'Background Control', 'wpspeedo-team' ), 'value' => 'center left' ],
				[ 'label' => _x( 'Center Right', 'Background Control', 'wpspeedo-team' ), 'value' => 'center right' ],
				[ 'label' => _x( 'Top Center', 'Background Control', 'wpspeedo-team' ), 'value' => 'top center' ],
				[ 'label' => _x( 'Top Left', 'Background Control', 'wpspeedo-team' ), 'value' => 'top left' ],
				[ 'label' => _x( 'Top Right', 'Background Control', 'wpspeedo-team' ), 'value' => 'top right' ],
				[ 'label' => _x( 'Bottom Center', 'Background Control', 'wpspeedo-team' ), 'value' => 'bottom center' ],
				[ 'label' => _x( 'Bottom Left', 'Background Control', 'wpspeedo-team' ), 'value' => 'bottom left' ],
				[ 'label' => _x( 'Bottom Right', 'Background Control', 'wpspeedo-team' ), 'value' => 'bottom right' ],
			],
			'default' => 'center center',
			'condition' => [
				'type' => [ 'gradient' ],
				'gradient_type' => 'radial',
			],
			'of_type' => 'gradient',
		];

		return $fields;
	}

	/**
	 * Get child default args.
	 *
	 * Retrieve the default arguments for all the child controls for a specific group
	 * control.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return array Default arguments for all the child controls.
	 */
	protected function get_child_default_args() {
		return [
			'types' => [ 'classic', 'gradient' ]
		];
	}

	/**
	 * Filter fields.
	 *
	 * Filter which controls to display, using `include`, `exclude`, `condition`
	 * and `of_type` arguments.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return array Control fields.
	 */
	protected function filter_fields() {
		$fields = parent::filter_fields();

		$args = $this->get_args();

		foreach ( $fields as &$field ) {
			if ( isset( $field['of_type'] ) && ! in_array( $field['of_type'], $args['types'] ) ) {
				unset( $field );
			}
		}

		return $fields;
	}

	/**
	 * Prepare fields.
	 *
	 * Process background control fields before adding them to `add_control()`.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @param array $fields Background control fields.
	 *
	 * @return array Processed fields.
	 */
	protected function prepare_fields( $fields ) {
		$args = $this->get_args();

		$background_types = self::get_background_types();

		$choose_types = [];

		foreach ( $args['types'] as $type ) {
			if ( isset( $background_types[ $type ] ) ) {
				$choose_types[ $type ] = $background_types[ $type ];
			}
		}

		$fields['type']['options'] = $choose_types;

		return parent::prepare_fields( $fields );
	}

	/**
	 * Get default options.
	 *
	 * Retrieve the default options of the background control. Used to return the
	 * default options while initializing the background control.
	 *
	 * @since 1.9.0
	 * @access protected
	 *
	 * @return array Default background control options.
	 */
	protected function get_default_options() {
		return [
			'popover' => [
				'starter_title' => _x( 'Background', 'Background Control', 'wpspeedo-team' ),
				'starter_name' => 'background',
				'starter_value' => 'yes',
				'settings' => [
					'separator' => 'after',
				],
			],
		];
	}
}