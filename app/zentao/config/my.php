<?php
$config->installed       = true;
$config->debug           = false;
$config->requestType     = 'PATH_INFO';
$config->db->host        = '127.0.0.1';
$config->db->port        = '3300';
$config->db->user        = 'root';
$config->db->prefix      = 'zt_';
$config->webRoot         = getWebRoot();

$config->db->name      = 'zentao';
$config->db->password  = '123456';
$config->default->lang = 'zh-cn';