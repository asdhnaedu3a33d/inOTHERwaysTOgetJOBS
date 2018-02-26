<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH.'libraries/rbac/SerializeObject.php');

class Role extends SerializeObject
{
    public $id_role;
    
    protected $permissions;
    
    public $menus = array();

    
    public function __construct(){
        $this->_CI =& get_instance();
        $this->_CI->load->model('rbac/rbac_model');
    }
    
    /**
     * Fungsi untuk mendapatkan seluruh permission pada role_id yang bersangkutan
     * input    : role_id
     * output   : object role yg berisi permission
     */
    public function getRolePerms($role_id){
        $role = new self();
        $results = $this->_CI->rbac_model->getRolePerms($role_id);
        $role->id_role = $role_id;
        
        foreach($results as $perm){
            $role->permissions[$perm->perm_name] = TRUE;
        }
        
        //populate menu
        $menus = $this->_CI->rbac_model->getRoleMenus($role_id);
        if($menus !== FALSE) {
            foreach($menus as $menu) {
                $role->menus[] = $menu->id_menu;
            }            
        }        
        
        return $role;
    }
    
    /**
     * Fungsi untuk mendapatkan apakah suatu permission ada pada role
     * input    : permission yang dicari
     * output   : true/false
     */
    public function hasPerm($permission){
        return isset($this->permissions[$permission]);
    }
    
    ///**
    // * Fungsi untuk memasukkan role baru
    // * input    : nama role
    // * output   : boolean
    // */
    //public function insertRole($role_name){
    //    return $this->CI->rbac_model->insertRole($role_name);
    //}
    //
    ///**
    // * fungsi untuk memasukkan array dari role untuk suatu user 
    // * input   : $id_user , $roles (array)
    // * output  : boolean
    // */
    //public function insertUserRole($id_user,$roles){
    //    return $this->CI->rbac_model->insertUserRole($id_user,$roles);
    //}
    //
    ///**
    // * Fungsi untuk memasukkan role dan permission yang baru
    // * input    : $role_id dan $perm_id
    // * output   : boolean
    // */ 
    //public function insertPerm($role_id,$perm_id){
    //    return $this->CI->rbac_model->insertPerm($role_id,$perm_id);
    //}
    //
    ///**
    // * Fungsi untuk menghapus seluruh role permission pada tabel role_perm
    // * input    :
    // * output   : boolean
    // */
    //public function deletePerms(){
    //    return $this->CI->rbac_model->deletePerms();
    //}
    //
    ///**
    // * fungsi untuk menghapus array dari role,dan seluruh yang berhubungan dengannya
    // * input    : $roles (array)
    // * output   : boolean 
    // */ 
    //public function deleteRoles($roles){
    //    
    //}
    //
    ///**
    // * fungsi untuk menghapus seluruh role dari id_user
    // * input    : $id_user
    // * output   : boolean
    // */
    //public function deleteUserRoles($id_user){
    //    return $this->CI->rbac_model->deleteUserRoles($id_user);
    //}
    
}


?>