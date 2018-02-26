<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH.'libraries/rbac/SerializeObject.php');

class User extends SerializeObject
{
    //array untuk menyimpan role yang di-assigned ke user
    public $roles;

    //unique id_user
    public $id;

    //
    public $active_role;

    public function __construct(){
        $this->_CI =& get_instance();
        $this->_CI->load->model('rbac/rbac_model');
    }

    /**
     * Fungsi untuk mendapatkan seluruh informasi pada user termasuk role dan permission
     * input    : $id_user
     * output   : object user
     */
    public function getByIdUser($id_user){

        $result = $this->_CI->rbac_model->getByIdUser($id_user);
        $result = (array) $result[0];

        //asumsi array $result berisi sesuai dengan nama properti
        if(!empty($result)){
            $user = new self();
            $user->loadData($result);
            $user->initRoles();
            $user->setActiveRole();
            return $user;
        } else {
            return NULL;
        }
    }

    /**
     * Fungsi untuk mendapatkan seluruh permission pada suatu user bedasarkan role_id yang didapat
     */
    protected function initRoles(){
        $result = $this->_CI->rbac_model->getRoleByIdUser($this->id);

        $this->roles = array();
        foreach($result as $role){
            $this->roles[$role->role_name] = (new Role())->getRolePerms($role->role_id);
        }
    }


    /**
     * Fungsi untuk mendapatkan apakah suatu permission ada pada user
     * input    : $perm yaitu permission
     * output   : boolean
     */
    public function hasPrivilegeInAll($perm){
        if(!is_null($this->roles)){
            foreach($this->roles as $role){
                if($role->hasPerm($perm)){
                    return TRUE;
                }
            }

        }
        return FALSE;
    }

    public function hasPrivilege($perm){
        if(!is_null($this->roles)){
            $role = $this->roles[$this->active_role];
            if($role->hasPerm($perm)){
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Fungsi untuk memeriksa apakah user memiliki suatu role
     * input    : $role_name
     * output   : boolean
     */
    public function hasRole($role_name){
        return isset($this->roles[$role_name]);
    }

    public function setActiveRole($role_name = NULL) {
        if(count($this->roles) > 0) {
            $role_keys = array_keys($this->roles);
            if(in_array($role_name, $role_keys)) {
                $this->active_role = $role_name;
            } else {
                if (empty($this->active_role)) {
                    //set default ke role yang pertama
                    $this->active_role = $role_keys[0];
                }
            }
        } else {
	        $this->active_role = NULL;
        }
    }

    public function getRolesName()
    {
        if(!is_null($this->roles)) {
            return array_keys($this->roles);
        }

        return FALSE;
    }

    public function getActiveIdMenu()
    {
    	if(!is_null($this->active_role)) {
	        $role = $this->roles[$this->active_role];
	        return $role->menus;
    	}

    	return array();
    }
}
?>
