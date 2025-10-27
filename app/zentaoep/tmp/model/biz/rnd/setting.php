<?php
global $app;
helper::import($app->getModulePath('', 'setting') . 'model.php');
class extsettingModel extends settingModel 
{
public function updateVersion($version)
{
    return parent::updateVersion($version);
}
//**//
}