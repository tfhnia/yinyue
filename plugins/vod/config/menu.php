<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

return array(

	//��̨�˵�����	
	'admin' => array(
		array(
			'name' => '��Ƶ����',
			'menu' => array(
				array(
					'name' => '��Ƶ����',
					'link' => 'admin/vod'
				),
				array(
					'name' => '��Ƶ����',
					'link' => 'admin/lists'
				),
				array(
					'name' => '�������',
					'link' => 'admin/type'
				),
				array(
					'name' => '��Ƶר��',
					'link' => 'admin/topic'
				),
				array(
					'name' => '�ղعۿ���¼',
					'link' => 'admin/opt/fav'
				),
				array(
					'name' => '��ƵAPI��Դ��',
					'link' => 'admin/apiku'
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
			'name' => '��Ƶ����',
			'menu' => array(
				array(
					'name' => '�ҵ���Ƶ',
					'link' => 'user/vod',
				),
				array(
					'name' => '�ϴ���Ƶ',
					'link' => 'user/vod/add',
				),
				array(
					'name' => '�ҵ��ղ�',
					'link' => 'user/fav',
				)
			)
		),
	),
);
