<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

return array(

	//��̨�˵�����	
	'admin' => array(
		array(
			'name' => '���ֹ���',
			'menu' => array(
				array(
					'name' => '���ֹ���',
					'link' => 'admin/singer'
				),
				array(
					'name' => '���ַ���',
					'link' => 'admin/lists'
				),
			),
		),
		
		array(
			'name' => '��̬����',
			'menu' => array(
				array(
					'name' => '���ɸ�����ҳ',
					'link' => 'admin/html/index'
				),
				array(
					'name' => '���ɷ���ҳ',
					'link' => 'admin/html/type'
				),
				array(
					'name' => '��������ҳ',
					'link' => 'admin/html/show'
				),
			),
		)
	
	),
	
	//��Ա���Ĳ˵�����
	'user' => array(
	),
	
	//��Ա�ռ�˵�����
	'home' => array(
	),
);
