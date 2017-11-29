<?php
	class Member{
		private $database;
		function Member(){
		//	$this->database = new db();
		}

		/*根据username取得所有字段的值*/
		public function getMemberMsgByUsername($username){
			$sql = "select * from ".DBPREFIX."member where username='".$username."'";
			return DB::get_all($sql);
		}

		/*根据数组插入新会员*/
		public function insertMemberByArray($mumber_msg){
			DB::insert(DBPREFIX."member", $mumber_msg);
		}

		/*修改密码*/
		public function updatePassword($username, $newpassword){
			return DB::update(DBPREFIX."member", array("password"=>md5($newpassword)), " username='".$username."'");
		}
	}
?>