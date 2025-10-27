<?php
global $app;
helper::import($app->getModulePath('', 'message') . 'model.php');
class extmessageModel extends messageModel 
{

    public function send($objectType, $objectID, $actionType, $actionID, $actor = '', $extra = '')
    {
$this->loadExtension('xuanxuan')->send($objectType, $objectID, $actionType, $actionID, $actor, $extra);
        if(defined('TUTORIAL')) return;

        $objectType     = strtolower($objectType);
        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);

        if(isset($messageSetting['mail']))
        {
            $actions = $messageSetting['mail']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
            {
                /* If it is an api call, get the request method set by the user. */
                global $config;
                $requestType = $config->requestType;
                if(defined('RUN_MODE') and RUN_MODE == 'api')
                {
                    $configRoot = $this->app->getConfigRoot();
                    if(file_exists($configRoot . 'my.php'))
                    {
                        include $configRoot . 'my.php';
                    }
                    else
                    {
                        include $configRoot . 'config.php';
                    }
                }

                if($objectType == 'feedback')
                {
                    $this->loadModel('feedback')->sendmail($objectID, $actionID);
                }
                else
                {
                    $this->loadModel('mail')->sendmail($objectID, $actionID);
                }

                if(defined('RUN_MODE') and RUN_MODE == 'api') $config->requestType = $requestType;
            }
        }

        if(isset($messageSetting['webhook']))
        {
            $actions = $messageSetting['webhook']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
            {
                $this->loadModel('webhook')->send($objectType, $objectID, $actionType, $actionID, $actor);
            }
        }
        if(isset($messageSetting['message']))
        {
            $actions = $messageSetting['message']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
            {
                $this->saveNotice($objectType, $objectID, $actionType, $actionID, $actor);
            }
        }
    }

//**//
}