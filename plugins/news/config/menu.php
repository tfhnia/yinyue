<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

return array(

	//��̨�˵�����	
	'admin' => array(
		array(
			'name' => '���Ź���',
			'menu' => array(
				array(
					'name' => '���Ź���',
					'link' => 'admin/news'
				),
				array(
					'name' => '���ŷ���',
					'link' => 'admin/lists'
				),
				array(
					'name' => '����ר��',
					'link' => 'admin/topic'
				),
				array(
					'name' => '�շ��Ķ���¼',
					'link' => 'admin/look'
				),
			),
		),
		
		array(
			'name' => '��̬����',
			'menu' => array(
				array(
					'name' => '���ɰ����ҳ',
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
				array(
					'name' => '����ר��ҳ',
					'link' => 'admin/html/topic'
				),
				array(
					'name' => '�����Զ���ҳ',
					'link' => 'admin/html/opt'
				),
			),
		)
	
	),
	
	//��Ա���Ĳ˵�����
	'user' => array(
		array(
			'name' => '���Ź���',
			'menu' => array(
				array(
					'name' => '�ҵ�����',
					'link' => 'user/news',
				),
				array(
					'name' => '��������',
					'link' => 'user/news/add',
				)
			)
		),
	),
);
