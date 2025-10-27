<?php
global $app;
helper::import($app->getModulePath('', 'common') . 'model.php');
class extcommonModel extends commonModel 
{
public function setCompany()
{
if(!empty($_SESSION['user']->feedback) or !empty($_COOKIE['feedbackView'])) $this->config->preferenceSetted = true;

if(!extension_loaded('ionCube Loader')) return parent::setCompany();
$this->loadExtension('zentaobiz')->setCompany();
    if(!extension_loaded('ionCube Loader')) return parent::setCompany();

    return $this->loadExtension('bizext')->setCompany();
}/**
 * Return a virtual license for local test.
 *
 * @static
 * @access public
 * @return object
 */
public function getLicense()
{
}public static function printLink($module, $method, $vars = '', $label = '', $misc = '', $print = true, $onlyBody = false, $type = '', $object = null, $programID = 0)
{
    if(strpos($module, '.') !== false) list($appName, $module) = explode('.', $module);
    if($module == 'trip' or $module == 'egress') return false;
    if(!commonModel::hasPriv($module, $method)) return false;

    $content  = '';
    $canClick = true;
    $link     = helper::createLink($module, $method, $vars, '', $onlyBody);
    if(!$canClick)
    {
        $misc = str_replace("class='", "disabled='disabled' class='disabled ", $misc);
        $misc = str_replace("data-toggle='modal'", ' ', $misc);
        $misc = str_replace("deleter", ' ', $misc);
        if(strpos($misc, "class='") === false) $misc .= " class='disabled' disabled='disabled'";
    }
    if($type == 'li') $content .= '<li' . ($canClick ? '' : " disabled='disabled' class='disabled'") . '>';
    $content .= html::a($canClick ? $link : 'javascript:void(0)', $label, '', $misc);
    if($type == 'li') $content .= '</li>';

    if($print !== false) echo $content;
    return $content;
}public static function printWebMainMenu()
{
    global $app, $lang;
    ksort($lang->webMenuOrder);

    $currentModule = $app->rawModule;
    $webMainMenus  = array();
    foreach($lang->webMenuOrder as $webMainMenuKey)
    {
        list($label, $moduleName, $methodName, $params) = explode('|', $lang->webMainNav->$webMainMenuKey);
        if(!common::hasPriv($moduleName, $methodName)) continue;

        $active = '';
        if($currentModule == $moduleName) $active = '1';
        if(isset($lang->navGroup->$currentModule) and ($lang->navGroup->$currentModule == $moduleName or $lang->navGroup->$currentModule == $webMainMenuKey)) $active = '1';
        if(isset($lang->webMenuGroup[$currentModule]) and $lang->webMenuGroup[$currentModule] == $webMainMenuKey) $active = '1';

        $webMainMenu = new stdclass();
        $webMainMenu->link   = helper::createLink($moduleName, $methodName, $params);
        $webMainMenu->active = $active;
        $webMainMenu->title  = $label;

        $webMainMenus[$webMainMenuKey] = $webMainMenu;
    }

    $maxBottomMenuCount = common::checkNotCN() ? 6 : 7;
    $i = 0;
    $count = count($webMainMenus);
    foreach($webMainMenus as $webMainMenuKey => $webMainMenu)
    {
        $i++;
        $active = $webMainMenu->active ? 'active' : '';
        if($count <= $maxBottomMenuCount or ($count > ($maxBottomMenuCount) && $i < ($maxBottomMenuCount)))
        {
            echo html::a($webMainMenu->link, "<div class='content'><div class='title'>{$webMainMenu->title}</div></div>", '', "class='item {$active}' data-id='$webMainMenuKey'");
        }

        if($count <= $maxBottomMenuCount) continue;

        if($i == ($maxBottomMenuCount))
        {
            $style = '{"position": "absolute", "top": "auto", "left": "auto", "bottom": 48, "right": 0}';
            echo "<a class='item' data-display='dropdown' data-placement='$style'>";
            echo "<div class='content'>";
            echo "<div class='title'>$lang->more</div>";
            echo "</div>\n</a>";
            echo "<div id='moreApp' class='list dropdown-menu'>";
        }
        if($i >= $maxBottomMenuCount)
        {
            echo "<a class='item text-center $active' href='{$webMainMenu->link}'>";
            echo "<div class='title'>{$webMainMenu->title}</div>";
            echo "</a>\n <div class='divider no-margin'></div>";
        }
        if($i == $count) echo "</div>";
    }
}

public static function printWebModuleMenu($moduleName)
{
    global $app, $lang;
    ksort($lang->$moduleName->webMenuOrder);

    $groupName = isset($lang->webMenuGroup[$moduleName]) ? $lang->webMenuGroup[$moduleName] : '';

    $currentModule  = strtolower($app->rawModule);
    $currentMethod  = strtolower($app->rawMethod);
    $moduleWebMenus = array();
    foreach($lang->$moduleName->webMenuOrder as $webMenuKey)
    {
        if($groupName)
        {
            $moduleWebMenu = $lang->$groupName->webMenu->$webMenuKey;
        }
        else
        {
            $moduleWebMenu = $lang->$moduleName->webMenu->$webMenuKey;
        }

        $link = is_array($moduleWebMenu) ? $moduleWebMenu['link'] : $moduleWebMenu;
        list($label, $linkModuleName, $linkMethodName, $params) = explode('|', $link);
        if(!common::hasPriv($linkModuleName, $linkMethodName)) continue;

        if(is_string($moduleWebMenu))
        {
            $moduleWebMenu = array();
            $moduleWebMenu['link'] = $link;
        }

        $active = '';
        if($currentModule == $linkModuleName and $currentMethod == strtolower($linkMethodName)) $active = '1';
        if($currentModule == $linkModuleName and isset($moduleWebMenu['alias']) and stripos(",{$moduleWebMenu['alias']},", ",{$currentMethod},") !== false) $active = '1';
        if(isset($moduleWebMenu['subModule']) and strpos(",{$moduleWebMenu['subModule']},", ",{$currentModule},") !== false) $active = '1';

        $moduleWebMenu = new stdclass();
        $moduleWebMenu->link   = helper::createLink($linkModuleName, $linkMethodName, $params);
        $moduleWebMenu->active = $active;
        $moduleWebMenu->title  = $label;

        $moduleWebMenus[$webMenuKey] = $moduleWebMenu;
    }

    foreach($moduleWebMenus as $webMenuKey => $moduleWebMenu)
    {
        $active = $moduleWebMenu->active ? 'active' : '';
        echo html::a($moduleWebMenu->link, $moduleWebMenu->title, '', "class='$active' data-id='{$webMenuKey}'");
    }
}public static function getLicensePropertyValue($propertyName)
{
    return ',,';
}
    public function checkPriv()
    {
$this->loadExtension('flow')->loadCustomLang();
$this->loadExtension('flow')->mergeLangFromFlow();
if($this->app->viewType == 'mhtml')
{
    $module = $this->app->getModuleName();
    $tab    = isset($this->lang->navGroup->$module) ? $this->lang->navGroup->$module : $module;

    setcookie('tab', $tab, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
    $_COOKIE['tab'] = $tab;
    $this->app->tab = $tab;
}
        try
        {
            $module = $this->app->getModuleName();
            $method = $this->app->getMethodName();
            if($this->app->isFlow)
            {
                $module = $this->app->rawModule;
                $method = $this->app->rawMethod;
            }

            $beforeValidMethods = array(
                'user'    => array('deny', 'logout'),
                'my'      => array('changepassword'),
                'message' => array('ajaxgetmessage'),
            );
            if(!empty($this->app->user->modifyPassword) and (!isset($beforeValidMethods[$module]) or !in_array($method, $beforeValidMethods[$module]))) return print(js::locate(helper::createLink('my', 'changepassword')));
            if(!$this->loadModel('user')->isLogon() and $this->server->php_auth_user) $this->user->identifyByPhpAuth();
            if(!$this->loadModel('user')->isLogon() and $this->cookie->za) $this->user->identifyByCookie();
            if($this->isOpenMethod($module, $method)) return true;

            if(isset($this->app->user))
            {
                if($this->app->tab == 'project')
                {
                    $this->resetProjectPriv();
                    if(commonModel::hasPriv($module, $method)) return true;
                }

                $this->app->user = $this->session->user;
                if(!commonModel::hasPriv($module, $method))
                {
                    if($module == 'story' and !empty($this->app->params['storyType']) and strpos(",story,requirement,", ",{$this->app->params['storyType']},") !== false) $module = $this->app->params['storyType'];
                    $this->deny($module, $method);
                }
            }
            else
            {
                $uri = $this->app->getURI(true);
                if($module == 'message' and $method == 'ajaxgetmessage')
                {
                    $uri = helper::createLink('my');
                }
                elseif(helper::isAjaxRequest())
                {
                    die(json_encode(array('result' => false, 'message' => $this->lang->error->loginTimeout))); // Fix bug #14478.
                }

                $referer = helper::safe64Encode($uri);
                die(js::locate(helper::createLink('user', 'login', "referer=$referer")));
            }
        }
        catch(EndResponseException $endResponseException)
        {
            die($endResponseException->getContent());
        }
    }

    public function isOpenMethod($module, $method)
    {
if($module == 'api' and $method == 'getlicenses') return true;
if($module == 'api')
{
    if($method == 'mobilegetlist'    ||
       $method == 'mobilegetinfo'    ||
       $method == 'mobilegetuser'    ||
       $method == 'mobilegetusers'   ||
       $method == 'mobilegethistory' ||
       $method == 'mobilecomment'    ||
       $method == 'mobilegetcustom') return true;
}
if($this->loadModel('user')->isLogon() or ($this->app->company->guest and $this->app->user->account == 'guest'))
{
    if($module == 'flow' and $method == 'browse')         return true;
    if($module == 'flow' and $method == 'create')         return true;
    if($module == 'flow' and $method == 'batchcreate')    return true;
    if($module == 'flow' and $method == 'edit')           return true;
    if($module == 'flow' and $method == 'operate')        return true;
    if($module == 'flow' and $method == 'batchoperate')   return true;
    if($module == 'flow' and $method == 'view')           return true;
    if($module == 'flow' and $method == 'delete')         return true;
    if($module == 'flow' and $method == 'link')           return true;
    if($module == 'flow' and $method == 'unlink')         return true;
    if($module == 'flow' and $method == 'export')         return true;
    if($module == 'flow' and $method == 'exporttemplate') return true;
    if($module == 'flow' and $method == 'import')         return true;
    if($module == 'flow' and $method == 'showimport')     return true;
    if($module == 'flow' and $method == 'report')         return true;

    if($module == 'workflowfield' and $method == 'addSqlVar')       return true;
    if($module == 'workflowfield' and $method == 'delSqlVar')       return true;
    if($module == 'workflowfield' and $method == 'buildVarControl') return true;

    if($module == 'workflowlabel' and $method == 'preview') return true;
    if($module == 'workflowlabel' and $method == 'removeFeatureTips') return true;

    if($module == 'workflowcondition' and $method == 'know') return true;

    if($module == 'workflowrule' and $method == 'checkregex') return true;
}
if($module == 'file' and $method == 'ajaxwopifiles') return true;
if($module == 'entry' and $method == 'visit')      return true;
if($module == 'integration' and $method == 'wopi') return true;
if($module == 'im' and $method == 'authorize')     return true;
        if(in_array("$module.$method", $this->config->openMethods)) return true;

        if($module == 'block' and $method == 'main' and isset($_GET['hash'])) return true;

        if($this->loadModel('user')->isLogon() or ($this->app->company->guest and $this->app->user->account == 'guest'))
        {
            if(stripos($method, 'ajax') !== false) return true;
            if($module == 'block') return true;
            if($module == 'my' and $method == 'guidechangetheme') return true;
            if($module == 'misc' and $method == 'downloadclient') return true;
            if($module == 'misc' and $method == 'changelog')  return true;
            if($module == 'tutorial' and $method == 'start')  return true;
            if($module == 'tutorial' and $method == 'index')  return true;
            if($module == 'tutorial' and $method == 'quit')   return true;
            if($module == 'tutorial' and $method == 'wizard') return true;
            if($module == 'product' and $method == 'showerrornone') return true;
        }
        return false;
    }

    public function loadConfigFromDB()
    {
if(defined('IN_USE') or (defined('RUN_MODE') and RUN_MODE == 'api'))
{
    $this->loadModel('setting');
    $xxItems  = $this->setting->getItems('owner=system&module=common&section=xuanxuan');
    $xxConfig = array();
    foreach($xxItems as $xxItem) $xxConfig[$xxItem->key] = $xxItem->value;
    if(empty($xxConfig['key']))
    {
        $this->setting->setItem('system.common.xuanxuan.turnon', '0');
        $this->setting->setItem('system.common.xuanxuan.key', $this->setting->computeSN());
    }
    if(!isset($xxConfig['chatPort']))       $this->setting->setItem('system.common.xuanxuan.chatPort', '11444');
    if(!isset($xxConfig['commonPort']))     $this->setting->setItem('system.common.xuanxuan.commonPort', '11443');
    if(!isset($xxConfig['ip']))             $this->setting->setItem('system.common.xuanxuan.ip', '0.0.0.0');
    if(!isset($xxConfig['uploadFileSize'])) $this->setting->setItem('system.common.xuanxuan.uploadFileSize', '20');
    if(!isset($xxConfig['https']) and !isset($xxConfig['isHttps'])) $this->setting->setItem('system.common.xuanxuan.https', 'off');
}
        /* Get configs of system and current user. */
        $account = isset($this->app->user->account) ? $this->app->user->account : '';
        if($this->config->db->name) $config = $this->loadModel('setting')->getSysAndPersonalConfig($account);
        $this->config->system   = isset($config['system']) ? $config['system'] : array();
        $this->config->personal = isset($config[$account]) ? $config[$account] : array();

        /* Overide the items defined in config/config.php and config/my.php. */
        if(isset($this->config->system->common))   $this->app->mergeConfig($this->config->system->common, 'common');
        if(isset($this->config->personal->common)) $this->app->mergeConfig($this->config->personal->common, 'common');

        $this->config->disabledFeatures = $this->config->disabledFeatures . ',' . $this->config->closedFeatures;
    }

//**//
}