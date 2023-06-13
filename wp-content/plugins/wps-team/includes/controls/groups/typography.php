<?php

namespace WPSpeedo_Team;

if ( ! defined( 'ABSPATH' ) ) exit;

class Group_Control_Typography extends Group_Base_Control {

	protected static $fields;

	private static $_scheme_fields_keys = [ 'font_family', 'font_weight' ];

	public static function get_scheme_fields_keys() {
		return self::$_scheme_fields_keys;
	}

	public static function get_type() {
		return 'typography';
	}

	protected function init_fields() {
		$fields = [];

		$fields['font_family'] = [
			'label' => _x( 'Family', 'Typography Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::FONT,
			'view_type' => 'arrange-1',
			'placeholder' => 'Default',
			'render_type' => 'template',
			'default' => '',
			'separator' => '',
		];

		$fields['font_size'] = [
			'label' => _x( 'Size', 'Typography Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SLIDER,
			'separator' => '',
			'size_units' => [ 'px', 'em', 'rem', 'vw' ],
			'unit' => 'px',
			'tablet_unit' => 'px',
			'small_tablet_unit' => 'px',
			'mobile_unit' => 'px',
			'responsive' => true,
		];

		$typo_weight_options = [
			[
				'label' => __( 'Default', 'wpspeedo-team' ),
				'value' => ''
			]
		];

		foreach ( array_merge( [ 'normal', 'bold' ], range( 100, 900, 100 ) ) as $weight ) {
			$typo_weight_options[] = [
				'label' => ucfirst( $weight ),
				'value' =>  $weight
			];
		}

		$fields['font_weight'] = [
			'label' => _x( 'Weight', 'Typography Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SELECT,
			'separator' => '',
			'view_type' => 'arrange-1',
			'placeholder' => 'Default',
			'default' => '',
			'options' => $typo_weight_options,
		];

		$fields['text_transform'] = [
			'label' => _x( 'Transform', 'Typography Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SELECT,
			'separator' => '',
			'view_type' => 'arrange-1',
			'placeholder' => 'Default',
			'default' => '',
			'options' => [
				[
					'label' => __( 'Default', 'wpspeedo-team' ),
					'value' => ''
				],
				[
					'label' => _x( 'Uppercase', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'uppercase'
				],
				[
					'label' => _x( 'Lowercase', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'lowercase'
				],
				[
					'label' => _x( 'Capitalize', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'capitalize'
				],
				[
					'label' => _x( 'Normal', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'none'
				]
			],
		];

		$fields['font_style'] = [
			'label' => _x( 'Style', 'Typography Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SELECT,
			'view_type' => 'arrange-1',
			'separator' => '',
			'placeholder' => 'Default',
			'default' => '',
			'options' => [
				[	'label' => __( 'Default', 'wpspeedo-team' ),
					'value' => ''
				],
				[	'label' => _x( 'Normal', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'normal'
				],
				[	'label' => _x( 'Italic', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'italic'
				],
				[	'label' => _x( 'Oblique', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'oblique'
				]
			],
		];

		$fields['text_decoration'] = [
			'label' => _x( 'Decoration', 'Typography Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SELECT,
			'view_type' => 'arrange-1',
			'separator' => '',
			'placeholder' => 'Default',
			'default' => '',
			'options' => [
				[	'label' => __( 'Default', 'wpspeedo-team' ),
					'value' => ''
				],
				[	'label' => _x( 'Underline', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'underline'
				],
				[	'label' => _x( 'Overline', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'overline'
				],
				[	'label' => _x( 'Line Through', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'line-through'
				],
				[	'label' => _x( 'None', 'Typography Control', 'wpspeedo-team' ),
					'value' => 'none'
				]
			],
		];

		$fields['line_height'] = [
			'label' => _x( 'Line-Height', 'Typography Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SLIDER,
			'separator' => '',
			'unit' => 'em',
			'tablet_unit' => 'em',
			'small_tablet_unit' => 'em',
			'mobile_unit' => 'em',
			'responsive' => true,
			'size_units' => [ 'px', 'em' ],
		];

		$fields['letter_spacing'] = [
			'label' => _x( 'Letter Spacing', 'Typography Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SLIDER,
			'separator' => '',
			'unit' => 'px',
			'tablet_unit' => 'px',
			'small_tablet_unit' => 'px',
			'mobile_unit' => 'px',
			'range' => [
				'px' => [
					'min' => -10,
					'max' => 10,
					'step' => 0.1,
				],
			],
			'responsive' => true,
		];

		return $fields;
	}

	protected function prepare_fields( $fields ) {
		array_walk(
			$fields, function( &$field, $field_name ) {

				if ( in_array( $field_name, [ 'typography', 'popover_toggle' ] ) ) {
					return;
				}

				$selector_value = ! empty( $field['selector_value'] ) ? $field['selector_value'] : str_replace( '_', '-', $field_name ) . ': {{VALUE}};';

				$field['selectors'] = [
					'{{SELECTOR}}' => $selector_value,
				];
			}
		);

		return parent::prepare_fields( $fields );
	}

	protected function add_group_args_to_field( $control_id, $field_args ) {
		$field_args = parent::add_group_args_to_field( $control_id, $field_args );

		$field_args['groupPrefix'] = $this->get_controls_prefix();
		$field_args['groupType'] = 'typography';

		$args = $this->get_args();

		if ( in_array( $control_id, self::get_scheme_fields_keys() ) && ! empty( $args['scheme'] ) ) {
			$field_args['scheme'] = [
				'type' => self::get_type(),
				'value' => $args['scheme'],
				'key' => $control_id,
			];
		}

		return $field_args;
	}

	protected function get_default_options() {
		return [
			'popover' => [
				'starter_name' => 'typography',
				'starter_title' => _x( 'Typography', 'Typography Control', 'wpspeedo-team' ),
				'settings' => [
					'groupType' => 'typography',
				],
			],
		];
	}
}
