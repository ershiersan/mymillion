<?php
	class Comments{
		private $database;
		function Comments(){
		//	$this->database = new db();
		}

		/*取得所有纪录
		 *$status="nolimit"	不限状态
		 */
		public function getAllRecord($status="nolimit"){
			$where = "";
			if ($status=="nolimit") {
				$where .= " where status='1' ";
			}
			$sql = "select * from ".DBPREFIX."comments ".$where." order by status asc,datetime desc";
			return DB::get_all($sql);
		}

		/*根据id和数据更新留言表
		 */
		function updateCommentsById($Id, $arrNewArray){
			return DB::update(DBPREFIX."comments", $arrNewArray, " id='".$Id."'");
		}

		/*根据id删除留言
		 */
		function deleteCommentsById($Id){
			return DB::delete(DBPREFIX."comments", " id='".$Id."'");
		}

		/*添加留言记录
		 */
		function insertComments($arrNewArray){
			return DB::insert(DBPREFIX."comments", $arrNewArray);
		}
	}
?>