<?php
if (!defined('CSCMSPATH')) exit('No permission resources');
return array(
           'DROP TABLE IF EXISTS `{prefix}dance`;',
           'DROP TABLE IF EXISTS `{prefix}dance_list`;',
           'DROP TABLE IF EXISTS `{prefix}dance_server`;',
           'DROP TABLE IF EXISTS `{prefix}dance_fav`;',
           'DROP TABLE IF EXISTS `{prefix}dance_down`;',
           'DROP TABLE IF EXISTS `{prefix}dance_play`;',
           'DROP TABLE IF EXISTS `{prefix}dance_topic`;'
);
