<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

return array(

	//��̨�˵�����	
	'admin' => array(
		array(
			'name' => '���ֹ���',
			'menu' => array(
				array(
					'name' => '��������',
					'link' => 'admin/dance'
				),
				array(
					'name' => '��������',
					'link' => 'admin/lists'
				),
				array(
					'name' => '��������',
					'link' => 'admin/server'
				),
				array(
					'name' => '����ר��',
					'link' => 'admin/topic'
				),
				array(
					'name' => '����ɨ��',
					'link' => 'admin/saomiao'
				),
				array(
					'name' => '�ղ����ؼ�¼',
					'link' => 'admin/opt/fav'
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
					'name' => '���ɲ���ҳ',
					'link' => 'admin/html/play'
				),
				array(
					'name' => '��������ҳ',
					'link' => 'admin/html/down'
				),
				array(
					'name' => '����ר��',
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
			'name' => '��������',
			'menu' => array(
				array(
					'name' => '�ҵĸ���',
					'link' => 'user/dance',
				),
				array(
					'name' => '�ϴ�����',
					'link' => 'user/dance/add',
				),
				array(
					'name' => '�ҵ�ר��',
					'link' => 'user/album',
				)
			)
		),
	),
);
