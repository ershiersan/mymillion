<?php
	class Price{
		private $database;
		function Price(){
		//	$this->database = new db();
		}

		/*取得所有纪录*/
		public function getAllRecord(){
			$sql = "select * from ".DBPREFIX."price order by startgrid asc";
			return DB::get_all($sql);
		}

		/*根据id和数据更新price
		 */
		function updatePriceById($Id, $arrNewArray){
			return DB::update(DBPREFIX."price", $arrNewArray, " id='".$Id."'");
		}

		/*根据id删除price
		 */
		function deletePriceById($Id){
			return DB::delete(DBPREFIX."price", " id='".$Id."'");
		}

		/*添加price记录
		 */
		function insertPrice($arrNewArray){
			return DB::insert(DBPREFIX."price", $arrNewArray);
		}
	}
?>