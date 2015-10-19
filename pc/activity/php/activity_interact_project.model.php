<?php  
Class activity_interact_project extends DB{
    private $table; 
    private $filter;
    private $limit;
    private $where;
	private $order;
    public function __construct(){   
        global $first; 
        parent::__construct();  
        $this->filter="*"; 
        $this->where="1"; 
        $this->limit="0 , 10"; 
		$this->order="date desc";
        $this->table=$first."activity_interact_project";
    }
	//select操作
    public function get(){
        $sql="select ".$this->filter." from ".$this->table." where ".$this->where;
        return $this->getrow($sql);
    }
	
	public function num(){
        $sql="select  COUNT(*) from ".$this->table;
        return $this->getrow($sql);
    }
    public function gets(){
         $sql="select ".$this->filter." from ".$this->table." where ".$this->where ." order by ".$this->order." limit ".$this->limit ;
	
		 return  $this->execsql($sql); 
    }
    public function where($array){
        foreach ($array as $k => $v){
            $this->where=$this->where." and ".$k."=".$v." ";    
        }
        return $this;
    }
    public function filter($array){ 
        $this->filter=" ".join(",",$array)." ";
		return $this;
    }
	public function limit($a,$b){
		$this->limit="".$a.",".$b."";
		return $this;
	}
	public function order($condition){
		$this->order=$condition;
		return $this;
	}
	//insert操作
    public function add($array){ 
        $array['date']=date('Y-m-d H:i:s');
		             global $file;
					fwrite($file,"+_+_+_+".$this->table);
					foreach($array as $k=>$v){
						fwrite($file,$k."=>".$v."______");
					}
					unset($array["id"]);
       return $this->insert($this->table,$array); 
    }
	//update操作
    public function update($array,$condition){
		//$array['date']=date('Y-m-d H:i:s');
        if( !is_array($array) || count($array)<=0) {
            return false;
        }
        $value = "";
		$table=$this->table;
        while( list($key,$val) = each($array))
        $value .= "$key = '$val',";
        $value .= substr( $value,0,-1);
        $sql = "update $table set $value where 1=1 and ".$condition.";";
        $stmt=mysql_query ( $sql,$this->conn );
		$res=mysql_affected_rows();
		if ($res){
			return true;
			
		}else {

			return false;
			
		}
    }
	//delete操作
    public function delete($condition){
        if( empty($condition) ) {
             return false;
        }
		$table=$this->table;
        $sql = "delete from $table where 1=1 and ".$condition.";";
        $stmt=mysql_query ( $sql,$this->conn );
		$res=mysql_affected_rows();
		if ($res){
			return true;
			
		}else {

			return false;
			
		}
    }
}
?>