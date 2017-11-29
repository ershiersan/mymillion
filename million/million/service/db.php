<?php
Class DB {

	private static $link_id;
	private static $handle;
	private static $is_log;
	private static $time;

	//构造函数
	public static function init() {
		self::$time = DB::microtime_float();
		require_once("config.db.php");
		DB::connect($db_config["hostname"], $db_config["username"], $db_config["password"], $db_config["database"], $db_config["pconnect"]);
		self::$is_log = $db_config["log"];
		if(self::$is_log){
			$handle = fopen($db_config["logfilepath"]."dblog.txt", "a+");
			self::$handle=$handle;
		}
	}
	
	//数据库连接
	public static function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0,$charset='utf8') {
		if( $pconnect==0 ) {
			self::$link_id = @mysql_connect($dbhost, $dbuser, $dbpw, true);
			if(!self::$link_id){
				DB::halt("数据库连接失败");
			}
		} else {
			self::$link_id = @mysql_pconnect($dbhost, $dbuser, $dbpw);
			if(!self::$link_id){
				DB::halt("数据库持久连接失败");
			}
		}
		if(!@mysql_select_db($dbname,self::$link_id)) {
			DB::halt('数据库选择失败');
		}
		@mysql_query("set names ".$charset);
	}
	
	//查询 
	public static function query($sql) {
		DB::write_log("查询 ".$sql);
		$query = mysql_query($sql,self::$link_id);
		if(!$query) DB::halt('Query Error: ' . $sql);
		return $query;
	}
	
	//获取一条记录（MYSQL_ASSOC，MYSQL_NUM，MYSQL_BOTH）				
	public static function get_one($sql,$result_type = MYSQL_ASSOC) {
		$query = DB::query($sql);
		$rt =& mysql_fetch_array($query,$result_type);
		DB::write_log("获取一条记录 ".$sql);
		return $rt;
	}

	//获取全部记录
	public static function get_all($sql,$result_type = MYSQL_ASSOC) {
		$query = DB::query($sql);
		$i = 0;
		$rt = array();
		while($row =& mysql_fetch_array($query,$result_type)) {
			$rt[$i]=$row;
			$i++;
		}
		DB::write_log("获取全部记录 ".$sql);
		return $rt;
	}
	
	//插入
	public static function insert($table,$dataArray) {
		$field = "";
		$value = "";
		if( !is_array($dataArray) || count($dataArray)<=0) {
			DB::halt('没有要插入的数据');
			return false;
		}
		while(list($key,$val)=each($dataArray)) {
			$field .="$key,";
			$value .="'$val',";
		}
		$field = substr( $field,0,-1);
		$value = substr( $value,0,-1);
		$sql = "insert into $table($field) values($value)";
		DB::write_log("插入 ".$sql);
		if(!DB::query($sql)) return false;
		return mysql_insert_id();
	}

	//插入完整sql
	public static function insertsql($sql) {
		DB::write_log("插入 ".$sql);
		if(!DB::query($sql)) return false;
		return mysql_insert_id();
	}

	//更新完整sql
	public static function updatesql($sql) {
		DB::write_log("更新 ".$sql);
		if(!DB::query($sql)) return false;
		return true;
	}

	//更新
	public static function update( $table,$dataArray,$condition="") {
		if( !is_array($dataArray) || count($dataArray)<=0) {
			DB::halt('没有要更新的数据');
			return false;
		}
		$value = "";
		while( list($key,$val) = each($dataArray))
		$value .= "$key = '$val',";
		$value .= substr( $value,0,-1);
		$sql = "update $table set $value where 1=1 and $condition";
		DB::write_log("更新 ".$sql);
		if(!DB::query($sql)) return false;
		return true;
	}

	//删除
	public static function delete( $table,$condition="") {
		if( empty($condition) ) {
			DB::halt('没有设置删除的条件');
			return false;
		}
		$sql = "delete from $table where 1=1 and $condition";
		DB::write_log("删除 ".$sql);
		if(!DB::query($sql)) return false;
		return true;
	}

	//返回结果集
	public static function fetch_array($query, $result_type = MYSQL_ASSOC){
		DB::write_log("返回结果集");
		return mysql_fetch_array($query, $result_type);
	}

	//获取记录条数
	public static function num_rows($results) {
		if(!is_bool($results)) {
			$num = mysql_num_rows($results);
			DB::write_log("获取的记录条数为".$num);
			return $num;
		} else {
			return 0;
		}
	}

	//释放结果集
	public static function free_result() {
		$void = func_get_args();
		foreach($void as $query) {
			if(is_resource($query) && get_resource_type($query) === 'mysql result') {
				return mysql_free_result($query);
			}
		}
		DB::write_log("释放结果集");
	}

	//获取最后插入的id
	public static function insert_id() {
		$id = mysql_insert_id(self::$link_id);
		DB::write_log("最后插入的id为".$id);
		return $id;
	}

	//关闭数据库连接
	protected static function close() {
		DB::write_log("已关闭数据库连接");
		return @mysql_close(self::$link_id);
	}

	//错误提示
	private static function halt($msg='') {
		$msg .= "\r\n".mysql_error();
		DB::write_log($msg);
		die($msg);
	}

	/*//析构函数
	public static function __destruct() {
		DB::free_result();
		$use_time = (DB::microtime_float())-(self::$time);
		DB::write_log("完成整个查询任务,所用时间为".$use_time);
		if(self::$is_log){
			fclose(self::$handle);
		}
	}*/
	
	//写入日志文件
	public static function write_log($msg=''){
		if(self::$is_log){
			$text = date("Y-m-d H:i:s")." ".$msg."\r\n";
			fwrite(self::$handle,$text);
		}
	}
	
	//获取毫秒数
	public static function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
}

?>