<?php if (!defined('FCPATH')) exit('No direct script access allowed');
return array (
  'Web_Mode' => 1,
  'Skins_Dir' => 'default/html/',
  'User_Dir' => false,
  'Mobile_Dir' => 'default/html/',
  'Mobile_Is' => 1,
  'Cache_Is' => 0,
  'Cache_Time' => 1800,
  'Ym_Mode' => 0,
  'Ym_Url' => 'singer.cscms.com',
  'User_Qx' => '1,2,3',
  'User_Dj_Qx' => '1,2,3,4',
  'Rewrite_Uri' => 
  array (
    'index' => 
    array (
      'title' => '�����ҳ����',
      'uri' => 'index',
      'url' => 'singer',
    ),
    'lists' => 
    array (
      'title' => '�б�ҳ����',
      'uri' => 'lists/index/{sort}/{id}/{page}',
      'url' => 'list-{sort}-{id}-{page}.html',
    ),
    'show' => 
    array (
      'title' => '������ҳ����',
      'uri' => 'show/index/{id}',
      'url' => 'show-{id}.html',
    ),
    'info' => 
    array (
      'title' => '��������ҳ����',
      'uri' => 'info/index/{id}',
      'url' => 'info-{id}.html',
    ),
    'music' => 
    array (
      'title' => '���ָ���ҳ����',
      'uri' => 'music/index/{sort}/{id}/{page}',
      'url' => 'music-{sort}-{id}-{page}.html',
    ),
    'pic' => 
    array (
      'title' => '����ͼƬҳ����',
      'uri' => 'pic/index/{sort}/{id}/{page}',
      'url' => 'pic-{sort}-{id}-{page}.html',
    ),
    'mv' => 
    array (
      'title' => '������Ƶҳ����',
      'uri' => 'mv/index/{sort}/{id}/{page}',
      'url' => 'mv-{sort}-{id}-{page}.html',
    ),
    'album' => 
    array (
      'title' => '����ר��ҳ����',
      'uri' => 'album/index/{sort}/{id}/{page}',
      'url' => 'album-{sort}-{id}-{page}.html',
    ),
  ),
  'Html_Uri' => 
  array (
    'index' => 
    array (
      'title' => '�����ҳ����',
      'url' => 'singer/',
      'check' => '1',
    ),
    'lists' => 
    array (
      'title' => '�б�ҳ����',
      'url' => 'singer/list/{sort}-{id}-{page}.html',
      'check' => '1',
    ),
    'show' => 
    array (
      'title' => '����ҳ����',
      'url' => 'singer/index/{id}.html',
      'check' => '1',
    ),
    'info' => 
    array (
      'title' => '��������ҳ����',
      'url' => 'singer/info/{id}.html',
      'check' => '1',
    ),
    'music' => 
    array (
      'title' => '���ָ���ҳ����',
      'url' => 'singer/music/{sort}-{id}-{page}.html',
      'check' => '1',
    ),
    'pic' => 
    array (
      'title' => '����ͼƬҳ����',
      'url' => 'singer/pic/{sort}-{id}-{page}.html',
      'check' => '1',
    ),
    'mv' => 
    array (
      'title' => '������Ƶҳ����',
      'url' => 'singer/mv/{sort}-{id}-{page}.html',
      'check' => '1',
    ),
    'album' => 
    array (
      'title' => '����ר��ҳ����',
      'url' => 'singer/album/{sort}-{id}-{page}.html',
      'check' => '1',
    ),
  ),
  'Seo' => 
  array (
    'title' => '����',
    'keywords' => '����',
    'description' => '����',
  ),
);?>