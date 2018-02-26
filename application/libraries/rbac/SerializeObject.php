<?php

class SerializeObject implements serializable {
    
    public function serialize(){
        $props = array();
        
        foreach($this as $var=>$value){
            //serialize only those variables not prefix with '_'
            if($var[0] !== '_')
                $props[$var] = $value;    
        }
        
        return serialize($props);
    }
    
    public function unserialize($data){
        $data=unserialize($data);
        $this->loadData($data);
    }

    public function loadData($data){
        foreach($data as $var=>$val){
            //katanya isset lebih cepat 4 kali daripada property_exists
            //namun isset akan mengembalikan FALSE jika field NULL, shg
            //digunakan keduanya
            //if(isset($this->$var) || property_exists($this, $var))
                //load only those variables not prefix with '_'
                if($var[0] !== '_')
                    $this->$var = $data[$var];
        }            
    }
    
    public function list_properties(){
        
        echo "<pre>";
        foreach($this as $var=>$value){
            echo "$var = '$value' <br>";
        }
        echo "</pre>";
    }
}

?>