<?php

namespace WPSpeedo_Team;

if ( ! defined( 'ABSPATH' ) ) exit;

class Group_Control_Border extends Group_Base_Control {

	protected static $fields;

	public static function get_type() {
		return 'border';
	}

	protected function init_fields() {
		$fields = [];

		$fields['border'] = [
			'label' => _x( 'Border Type', 'Border Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::SELECT,
			'separator' => '',
			'view_type' => 'arrange-1',
			'placeholder' => __( 'None', 'wpspeedo-team' ),
			'options' => [
				[ 'label' => __( 'None', 'wpspeedo-team' ), 'value' => '' ],
				[ 'label' => _x( 'Solid', 'Border Control', 'wpspeedo-team' ), 'value' => 'solid' ],
				[ 'label' => _x( 'Double', 'Border Control', 'wpspeedo-team' ), 'value' => 'double' ],
				[ 'label' => _x( 'Dotted', 'Border Control', 'wpspeedo-team' ), 'value' => 'dotted' ],
				[ 'label' => _x( 'Dashed', 'Border Control', 'wpspeedo-team' ), 'value' => 'dashed' ],
				[ 'label' => _x( 'Groove', 'Border Control', 'wpspeedo-team' ), 'value' => 'groove' ]
			]
		];

		$fields['width'] = [
			'label' => _x( 'Width', 'Border Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::DIMENSIONS,
			'separator' => '',
			'condition' => [
				'border!' => '',
			],
			'responsive' => true,
		];

		$fields['color'] = [
			'label' => _x( 'Color', 'Border Control', 'wpspeedo-team' ),
			'type' => Controls_Manager::COLOR,
			'separator' => '',
			'default' => '',
			'condition' => [
				'border!' => '',
			],
		];

		return $fields;
	}

	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}

}