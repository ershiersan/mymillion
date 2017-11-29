<?php
	class FAQ{
		private $database;
		function FAQ(){
		//	$this->database = new db();
		}

		/*取得所有纪录*/
		public function getAllRecord(){
			$sql = "select * from ".DBPREFIX."faq order by intorder asc";
			return DB::get_all($sql);
		}

		/*根据id和数据更新FAQ
		 */
		function updateFAQById($Id, $arrNewArray){
			return DB::update(DBPREFIX."faq", $arrNewArray, " id='".$Id."'");
		}

		/*根据id删除FAQ
		 */
		function deleteFAQById($Id){
			return DB::delete(DBPREFIX."faq", " id='".$Id."'");
		}

		/*添加FAQ记录
		 */
		function insertFAQ($arrNewArray){
			return DB::insert(DBPREFIX."faq", $arrNewArray);
		}
	}
?>