<?php
// This file is generated. Do not modify it manually.
return array(
	'build' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'gatherpress/references',
		'version' => '0.1.0',
		'title' => 'GatherPress References',
		'category' => 'widgets',
		'icon' => 'awards',
		'description' => 'Display event references including clients, festivals, and awards.',
		'example' => array(
			
		),
		'attributes' => array(
			'productionId' => array(
				'type' => 'number',
				'default' => 0
			),
			'year' => array(
				'type' => 'string',
				'default' => ''
			),
			'referenceType' => array(
				'type' => 'string',
				'default' => 'all',
				'enum' => array(
					'all',
					'ref_client',
					'ref_festival',
					'ref_award'
				)
			),
			'headingLevel' => array(
				'type' => 'number',
				'default' => 2
			),
			'metadata' => array(
				'type' => 'object',
				'default' => array(
					'name' => 'GatherPress References'
				)
			)
		),
		'supports' => array(
			'html' => false,
			'color' => array(
				'background' => true,
				'text' => true,
				'link' => true,
				'gradients' => true,
				'__experimentalDefaultControls' => array(
					'background' => true,
					'text' => true
				)
			),
			'spacing' => array(
				'margin' => true,
				'padding' => true,
				'blockGap' => true,
				'__experimentalDefaultControls' => array(
					'margin' => true,
					'padding' => true,
					'blockGap' => true
				)
			),
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'fontFamily' => true,
				'fontWeight' => true,
				'fontStyle' => true,
				'textTransform' => true,
				'letterSpacing' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => true,
					'fontFamily' => true
				)
			),
			'__experimentalBorder' => array(
				'color' => true,
				'radius' => true,
				'style' => true,
				'width' => true,
				'__experimentalDefaultControls' => array(
					'color' => true,
					'radius' => true
				)
			)
		),
		'style' => 'file:./style-index.css',
		'textdomain' => 'gatherpress-references',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'render' => 'file:./render.php'
	)
);
