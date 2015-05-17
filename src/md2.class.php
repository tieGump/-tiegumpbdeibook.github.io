<?php
//require_once('ascii.class.php');
class md2 {
	private $add_arr=array(
	'add','by','mode','minus'
	);
	private $cut_arr=array(
	'minus','InAddition','unMode','add'
	);
    function md2() {
    }
    function md2Add($str){
    	$str_end='';
    	 $str=ascii::encode($str);
    	$str=substr($str,0,strlen($str)-1);
    	$begin=rand(0,3);
    	$key=$this->getKey();
    	$arr=explode(';',$str);
    	foreach($arr as $value){
    		$str_end.=';'.$this->oneAdd($begin,$key,$value);
    	}
    	$arr_end['key']=$begin.$key;
    	$arr_end['code']=substr($str_end,1);
    	return $arr_end;	
    	
    }
    function getKey(){
    	$str='';
    	for($i=0;$i<=4;$i++)
    	{
    	$str.=rand(1,9);
    	}
    	return $str;
    }
    function oneAdd($begin,$key,$num){
    	$end='';
    	for($i=0;$i<5;$i++){
    		if($end='');
    		$end=$num;    		
    		$func_key=($begin+$i)>3?($begin+$i-4):($begin+$i);
    		$func=$this->add_arr[$func_key];
    		$end=$this->$func($end,$key[$i]);
    	
    	}
    	return $end;
    	
    }
    function md2Cut($str,$key){
    	$str_end='';
    	$begin=substr($key,0,1);
    	$key=substr($key,1,strlen($key));
    	$arr=explode(';',$str);
    	foreach($arr as $value){
    	$str_end.=';'.$this->cutOne($value,$key,$begin);
    	}
    	return ascii::decode($str_end);
    }
    function cutOne($num,$key,$begin){
    for($i=0;$i<5;$i++){
    		if($end='');
    		$end=$num;    		
    		$func_key=($begin+$i)>3?($begin+$i-4):($begin+$i);
    		$func=$this->cut_arr[$func_key];
    		$end=$this->$func($end,$key[$i]);
    	
    	}
    	return $end;
    }
    /**加*/
    function add($num,$key){
    	return $num+$key*10;
    }
    /**减*/
    function minus($num,$key){
    	return $num-$key*10;
    }
    /**乘*/
    function by($num,$key){
    	return $num*$key+10;
    }
    /**除*/
    function InAddition($num,$key){
    	return ($num-10)/$key;
    }
    /**取模*/
    function mode($num,$key){
    	
    	$end=floor($num/$key);
    	$end.=$num%$key;
    	return $end;
    }
    /**取模反*/
    function unMode($num,$key){
    	$one=substr($num,-1);
    	$two=substr($num,0,strlen($num)-1);
    	return $two*$key+$one;
    }
}

?>