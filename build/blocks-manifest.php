<?php
// This file is generated. Do not modify it manually.
return array(
	'build' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'gatherpress/references',
		'version' => '0.1.0',
		'title' => 'GatherPress References',
		'category' => 'gatherpress',
		'icon' => 'awards',
		'description' => 'Display references such as clients, festivals and awards in a structured, chronological format.',
		'example' => array(
			
		),
		'attributes' => array(
			'postType' => array(
				'type' => 'string',
				'default' => ''
			),
			'refTermId' => array(
				'type' => 'number',
				'default' => 0
			),
			'year' => array(
				'type' => 'number',
				'default' => 0
			),
			'referenceType' => array(
				'type' => 'string',
				'default' => 'all'
			),
			'headingLevel' => array(
				'type' => 'number',
				'default' => 2
			),
			'yearSortOrder' => array(
				'type' => 'string',
				'default' => 'desc',
				'enum' => array(
					'asc',
					'desc'
				)
			),
			'metadata' => array(
				'type' => 'object',
				'default' => array(
					'name' => 'References'
				)
			)
		),
		'supports' => array(
			'html' => false,
			'color' => array(
				'background' => true,
				'text' => true,
				'link' => false,
				'gradients' => true,
				'__experimentalDefaultControls' => array(
					'background' => true,
					'text' => true
				)
			),
			'spacing' => array(
				'margin' => true,
				'padding' => true,
				'blockGap' => false,
				'__experimentalDefaultControls' => array(
					'margin' => true,
					'padding' => true,
					'blockGap' => false
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
