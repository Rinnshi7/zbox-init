<?php
global $app;
helper::import($app->getModulePath('', 'company') . 'model.php');
class extcompanyModel extends companyModel 
{
/**
 * The model file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
public function getEffort($parent, $begin, $end, $product = 0, $project = 0, $execution = 0, $user = '', $showUser = 'all', $userType = '')
{
    return $this->loadExtension('calendar')->getEffort($parent, $begin, $end, $product, $project, $execution, $user, $showUser, $userType);
}

public function getTodo($parent, $begin, $end, $pager = null)
{
    return $this->loadExtension('calendar')->getTodo($parent, $begin, $end, $pager);
}

public function getChildren($deptID)
{
    return $this->loadExtension('calendar')->getChildren($deptID);
}public function getOutsideCompanys()
{
    $companys = $this->dao->select('id, name')->from(TABLE_COMPANY)->where('id')->ne(1)->fetchPairs();

    return array('' => '') + $companys;
}

public function getCompanyUserPairs()
{
    $pairs = $this->dao->select("t1.account, CONCAT_WS('/', t2.name, t1.realname)")->from(TABLE_USER)->alias('t1')
        ->leftJoin(TABLE_COMPANY)->alias('t2')
        ->on('t1.company = t2.id')
        ->fetchPairs();

    return $pairs;
}
//**//
}