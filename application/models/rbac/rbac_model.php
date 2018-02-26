<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rbac_model extends CI_Model
{
    private $_tbl_user = 'm_users';
    
    public function __construct(){
        $this->load->database();
    }
    
    /**
     * model fungsi untuk mendapatkan permission dari suatu role
     * input : $role_id
     * output : array yang bersesuaian dengan permission dari role_id
     */
     public function getRolePerms($role_id){
        $sql = "SELECT t2.perm_name
                FROM rbac_role_perm as t1
                JOIN rbac_permissions as t2 ON t1.perm_id = t2.perm_id
                WHERE t1.role_id = ?";
                
        $query = $this->db->query($sql, array($role_id));
        return $query->result(); 
    }
    
    /**
     * model untuk mendapatkan seluruh informasi dari user bedasarkan nip
     * input : $nip yaitu nip dari user
     * output : array yang berisi seluruh informasi dari user
     */
    
    function getByNip($nip){
        $sql = "SELECT id_user as id, username, password
                FROM {$this->_tbl_user}
                WHERE id_user = ?
                ";

        $query = $this->db->query($sql, array($nip));
        return $query->result(); 
    }
    
    /**
     * model untuk mendapatkan seluruh informasi dari user bedasarkan id_user
     * input : $id_user yaitu id_user dari user
     * output : array yang berisi seluruh informasi dari user
     */
    
    function getByIdUser($id_user){
        $sql = "SELECT id_user as id, username, password
                FROM {$this->_tbl_user}
                WHERE id_user = ?
                ";

        $query = $this->db->query($sql, array($id_user));
        return $query->result();
    }
    
    /**
     * model fungsi untuk mendapatkan seluruh role dari suatu user bedasarkan id_user
     * input : $id_user yaitu id_user dari user
     * output : array role dari user yang bersangkutan
     */ 
    function getRoleByIdUser($id_user){
        $sql = "SELECT t1.role_id , t2.role_name
                FROM rbac_user_role as t1
                JOIN rbac_roles as t2 ON t1.role_id = t2.role_id
                WHERE t1.id_user = ?";
                
        $query = $this->db->query($sql, array($id_user));
        return $query->result();
    }
    
    function getRoleMenus($role_id)
    {
        $sql = "
            SELECT DISTINCT d.id_menu
            FROM rbac_roles a
                INNER JOIN rbac_role_perm b ON a.role_id=b.role_id
                INNER JOIN rbac_permissions c ON b.perm_id=c.perm_id
                INNER JOIN rbac_menu d ON c.id_menu=d.id_menu
            WHERE a.role_id = ?
            ORDER BY d.parent ASC, d.weight ASC
        ";
        
        $query = $this->db->query($sql, array($role_id));
        if($query->num_rows() > 0) {
            return $query->result();
        }
        
        return FALSE;
    }
    
    /**
     * model fungsi untuk memasukkan role baru
     * input    : nama role
     * output   : boolean
     */
    public function insertRole($role_name){
        $sql = "INSERT INTO rbac_roles (role_name)
                VALUES (?)";
                
        return $this->db->query($sql, array($role_name));
    }
     
     /**
      * model untuk memasukkan array dari role untuk suatu user 
      * input   : $id_user , $role_id (array)
      * output  : boolean
      */
    public function insertUserRole($id_user,$roles){
        
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();
        
        foreach($roles as $role){
            $sql = "INSERT INTO rbac_user_role (id_user,role_id)
                    VALUES (?,?)";
                    
            $this->db->query($sql, array($id_user,$role));
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }
    
    /**
     * model untuk menghapus seluruh role dari id_user
     * input    : $id_user
     * output   : boolean
     */
    public function deleteUserRoles($id_user){
        $sql = "DELETE FROM rbac_user_role
                WHERE id_user = ?";

        return $this->db->query($sql, array($id_user));
    }
    
    /**
     * model untuk memasukkan role dan permission yang baru
     * input    : $role_id dan $perm_id
     * output   : boolean
     */ 
    public function insertPerm($role_id,$perm_id){
        $sql = "INSERT INTO rbac_role_perm (role_id,perm_id)
                VALUES (?,?)";
                
        return $this->db->query($sql, array($role_id,$perm_id));
    }
    
    /**
     * Model untuk menhapus seluruh role permission pada tabel role_perm
     * input    :
     * output   : boolean
     */
    public function deletePerms(){
        $sql = "TRUNCATE rbac_role_perm";
        
        return $this->db->query($sql);
    }
    
    public function getMenuById($id_user){
        $sql = "SELECT DISTINCT rbac_menu.* FROM rbac_role_perm
                INNER JOIN rbac_roles
                ON rbac_roles.role_id=rbac_role_perm.role_id
                INNER JOIN rbac_permissions
                ON rbac_role_perm.perm_id=rbac_permissions.perm_id
                INNER JOIN rbac_menu
                ON rbac_permissions.id_menu=rbac_menu.id_menu
                WHERE rbac_roles.role_id IN ( 
                		SELECT rbac_roles.role_id FROM rbac_user_role 
                		INNER JOIN `user`
                		ON `user`.id_user=rbac_user_role.id_user
                		INNER JOIN rbac_roles
                		ON rbac_user_role.role_id=rbac_roles.role_id
                		WHERE `user`.id_user = ?
                )";
        
        return $this->db->query($sql, array($id_user));
    }
}

?>