<?php
global $app;
helper::import($app->getModulePath('', 'admin') . 'model.php');
class extadminModel extends adminModel 
{
public function blockStatus($block = null)
{   
    return $this->loadExtension('xuanxuan')->blockStatus($block);
}   

public function blockStatistics($block = null)
{
    return $this->loadExtension('xuanxuan')->blockStatistics($block);
}
//**//
}