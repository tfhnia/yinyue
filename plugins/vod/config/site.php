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
  'Ym_Url' => 'vod.cscms.com',
  'User_Qx' => '',
  'User_Dj_Qx' => '',
  'Rewrite_Uri' => 
  array (
    'index' => 
    array (
      'title' => '�����ҳ����',
      'uri' => 'index',
      'url' => 'vod',
    ),
    'lists' => 
    array (
      'title' => '�б�ҳ����',
      'uri' => 'lists/index/{sort}/{id}/{page}',
      'url' => 'list-{sort}-{id}-{page}.html',
    ),
    'show' => 
    array (
      'title' => '����ҳ����',
      'uri' => 'show/index/id/{id}',
      'url' => 'show-{id}.html',
    ),
    'play' => 
    array (
      'title' => '����ҳ����',
      'uri' => 'play/index/id/{id}/{zu}/{ji}',
      'url' => 'play-{id}-{zu}-{ji}.html',
    ),
    'down' => 
    array (
      'title' => '����ҳ����',
      'uri' => 'down/index/id/{id}/{zu}/{ji}',
      'url' => 'down-{id}-{zu}-{ji}.html',
    ),
    'topic/lists' => 
    array (
      'title' => 'ר���б����',
      'uri' => 'topic/lists/{sort}/{page}',
      'url' => 'topic-lists-{sort}-{page}.html',
    ),
    'topic/show' => 
    array (
      'title' => 'ר�����ݹ���',
      'uri' => 'topic/show/{id}',
      'url' => 'topic-show-{id}.html',
    ),
  ),
  'Html_Uri' => 
  array (
    'index' => 
    array (
      'title' => '�����ҳ����',
      'url' => 'vod/',
      'check' => '1',
    ),
    'lists' => 
    array (
      'title' => '�б�ҳ����',
      'url' => 'vod/list-{sort}-{id}-{page}.html',
      'check' => '1',
    ),
    'show' => 
    array (
      'title' => '����ҳ����',
      'url' => 'vod/show-{id}.html',
      'check' => '1',
    ),
    'play' => 
    array (
      'title' => '����ҳ����',
      'url' => 'vod/play-{id}/{zu}/{ji}/',
      'check' => '0',
    ),
    'topic/lists' => 
    array (
      'title' => 'ר���б����',
      'url' => 'topic/{sort}-{page}.html',
      'check' => '1',
    ),
    'topic/show' => 
    array (
      'title' => 'ר�����ݹ���',
      'url' => 'topic/show-{id}.html',
      'check' => '1',
    ),
  ),
  'Seo' => 
  array (
    'title' => '��Ƶ',
    'keywords' => '��Ƶ',
    'description' => '��Ƶ',
  ),
);?>
