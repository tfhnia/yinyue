<?php if (!defined('FCPATH')) exit('No direct script access allowed');
return array (
  'Web_Mode' => 1,
  'Skins_Dir' => 'default/html/',
  'User_Dir' => 'default/html/',
  'Mobile_Dir' => 'default/html/',
  'Mobile_Is' => 1,
  'Cache_Is' => 0,
  'Cache_Time' => 1800,
  'Ym_Mode' => 0,
  'Ym_Url' => 'dj.cscms.com',
  'User_Qx' => '',
  'User_Dj_Qx' => '',
  'Rewrite_Uri' => 
  array (
    'index' => 
    array (
      'title' => '�����ҳ����',
      'uri' => 'index',
      'url' => 'dance',
    ),
    'lists' => 
    array (
      'title' => '�б�ҳ����',
      'uri' => 'lists/index/{sort}/{id}/{page}',
      'url' => 'list-{sort}-{id}-{page}.html',
    ),
    'play' => 
    array (
      'title' => '����ҳ����',
      'uri' => 'play/index/id/{id}',
      'url' => 'play-{id}.html',
    ),
    'down' => 
    array (
      'title' => '����ҳ����',
      'uri' => 'down/index/id/{id}',
      'url' => 'down-{id}.html',
    ),
    'topic' => 
    array (
      'title' => 'ר����ҳ����',
      'uri' => 'topic/index/{sort}/{page}',
      'url' => 'topic-{sort}-{page}.html',
    ),
    'topic/lists' => 
    array (
      'title' => 'ר���������',
      'uri' => 'topic/lists/{sort}/{id}/{page}',
      'url' => 'topic-{sort}-{id}-{page}.html',
    ),
    'topic/show' => 
    array (
      'title' => 'ר�����ݹ���',
      'uri' => 'topic/show/{id}/{page}',
      'url' => 'topic-show-{id}-{page}.html',
    ),
  ),
  'Html_Uri' => 
  array (
    'index' => 
    array (
      'title' => '�����ҳ����',
      'url' => 'music/',
      'check' => '1',
    ),
    'lists' => 
    array (
      'title' => '�б�ҳ����',
      'url' => 'music/list-{sort}-{id}-{page}.html',
      'check' => '1',
    ),
    'play' => 
    array (
      'title' => '����ҳ����',
      'url' => 'music/play/{id}.html',
      'check' => '1',
    ),
    'down' => 
    array (
      'title' => '����ҳ����',
      'url' => 'music/down/{id}.html',
      'check' => '1',
    ),
    'topic/lists' => 
    array (
      'title' => 'ר���б����',
      'url' => 'album/{sort}/{id}/{page}.html',
      'check' => '1',
    ),
    'topic/show' => 
    array (
      'title' => 'ר�����ݹ���',
      'url' => 'album/show/{id}-{page}.html',
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
