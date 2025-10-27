<?php
global $app;
helper::import($app->getModulePath('', 'action') . 'model.php');
class extactionModel extends actionModel 
{
/**
 * Get user actions of password change and login since last two xxd poll.
 *
 * @param  string $action
 * @access public
 * @return array
 */
public function getListSinceLastPoll($action = '')
{
    $lastPoll = date(DT_DATETIME1, strtotime($this->config->xxd->lastPoll . ' - ' . 2 * $this->config->xuanxuan->pollingInterval . ' second'));
    $actions  = array();
    if($action === 'changepassword')
    {
        $actions = $this->dao->select('t1.id, t1.objectID, t1.date')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_HISTORY)->alias('t2')->on('t1.id = t2.action')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.objectID = t3.id')
            ->where('t3.clientStatus')->ne('offline')
            ->andWhere('t1.objectType')->eq('user')
            ->andWhere('t2.field')->eq('password')
            ->andWhere('t1.action')->eq('edited')
            ->andWhere('t1.date')->gt($lastPoll)
            ->orderBy('t1.`date`_desc')
            ->fetchAll();
    }
    elseif($action === 'loginxuanxuan')
    {
        $actions = $this->dao->select('id, objectID, date')->from(TABLE_ACTION)
            ->where('date')->gt($lastPoll)
            ->andWhere('action')->eq('loginxuanxuan')
            ->orderBy('`date`_desc')
            ->fetchAll();
    }

    $uniqueActions = array();
    foreach($actions as $action)
    {
        $exist = false;
        foreach($uniqueActions as $uniqueAction)
        {
            if($uniqueAction->objectID === $action->objectID)
            {
                $exist = true;
                break;
            }
        }
        if(!$exist) $uniqueActions[] = $action;
    }

    return $uniqueActions;
}
    public function create($objectType, $objectID, $actionType, $comment = '', $extra = '', $actor = '', $autoDelete = true)
    {
if(strtolower($actionType) == 'reconnectxuanxuan' or strtolower($actionType) == 'loginxuanxuan')
{
    $ip   = helper::getRemoteIp();
    $last = $this->server->request_time;
    $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($actor)->exec();
}
        if(strtolower($actionType) == 'commented' and empty($comment)) return false;

        $actor      = $actor ? $actor : $this->app->user->account;
        $actionType = strtolower($actionType);
        $actor      = ($actionType == 'openedbysystem' or $actionType == 'closedbysystem') ? '' : $actor;
        if($actor == 'guest' and $actionType == 'logout') return false;

        $objectType = str_replace('`', '', $objectType);

        $action             = new stdclass();
        $action->objectType = strtolower($objectType);
        $action->objectID   = $objectID;
        $action->actor      = $actor;
        $action->action     = $actionType;
        $action->date       = helper::now();
        $action->extra      = $extra;
        if(!defined('IN_UPGRADE')) $action->vision = $this->config->vision;

        if($objectType == 'story' and strpos(',reviewpassed,reviewrejected,reviewclarified,reviewreverted,synctwins,', ",$actionType,") !== false) $action->actor = $this->lang->action->system;

        /* Use purifier to process comment. Fix bug #2683. */
        if(empty($comment)) $comment = '';
        $action->comment = fixer::stripDataTags($comment);

        /* Process action. */
        if($this->post->uid)
        {
            $action = $this->loadModel('file')->processImgURL($action, 'comment', $this->post->uid);
            if($autoDelete) $this->file->autoDelete($this->post->uid);
        }

        /* Get product project and execution for this object. */
        $relation          = $this->getRelatedFields($action->objectType, $objectID, $actionType, $extra);
        $action->product   = $relation['product'];
        $action->project   = (int)$relation['project'];
        $action->execution = (int)$relation['execution'];
        $this->dao->insert(TABLE_ACTION)->data($action)->autoCheck()->exec();
        $actionID = $this->dao->lastInsertID();

        if($this->post->uid) $this->file->updateObjectID($this->post->uid, $objectID, $objectType);

        /* Call the message notification function. */
        $this->loadModel('message')->send(strtolower($objectType), $objectID, $actionType, $actionID, $actor, $extra);

        /* Add index for global search. */
        $this->saveIndex($objectType, $objectID, $actionType);

        return $actionID;
    }

//**//
}