<?php
/**
 * this file must be stored in:
 * protected/components/WebUser.php
 */
class WebUser extends CWebUser
{
    // Store model to not repeat query.
    private $_model;
 
    /**
    * Return first name.
    * access it by Yii::app()->user->type
    */
    public function getType()
    {
        $user = $this->loadUser(Yii::app()->user->id);
        $type=$user->id_type_of_user;
        return $type;
    }

    /**
     * This is a function that checks the field 'role'
     * in the User model to be equal to 1, that means it's admin
     * access it by Yii::app()->user->isAdmin()
     */
    public function isAdmin()
    {
        $user = $this->loadUser(Yii::app()->user->id);
        return intval($user->superuser) == 1;
    }

    /**
     * Load user model.
     */
    protected function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null)
                $this->_model=Users::model()->findByPk($id);
        }
        return $this->_model;
    }
}
?>