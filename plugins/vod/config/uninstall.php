<?php
if (!defined('CSCMSPATH')) exit('No permission resources');
return array(
           'DROP TABLE IF EXISTS `{prefix}vod`;',
           'DROP TABLE IF EXISTS `{prefix}vod_type`;',
           'DROP TABLE IF EXISTS `{prefix}vod_list`;',
           'DROP TABLE IF EXISTS `{prefix}vod_fav`;',
           'DROP TABLE IF EXISTS `{prefix}vod_look`;',
           'DROP TABLE IF EXISTS `{prefix}vod_topic`;'
);
