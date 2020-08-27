<?php
namespace console\controllers;

use yii\rbac\Rule;
use common\models\DuocTicket;

/**
 * Checks if authorID matches user passed via params
 */
class PhieuduocRule extends Rule
{
    public $name = 'isUserPhieuDuoc';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if(isset($params['id_phieu'])) {
            $info = DuocTicket::findOne(['id' => $params['id_phieu']]);
            if($info && $info->id_user == $user){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }   
    }
}
?>