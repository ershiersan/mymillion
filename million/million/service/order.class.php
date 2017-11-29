<?php
	class Order{
		private $database;
		function Order(){
		//	$this->database = new db();
		}

		/*取得所有可被显示在页面上的订单
		 *包括未支付，已支付未审核，已支付拒绝审核--锁定；已付通过审核--正常显示
		 *首先对未支付的订单进行三天自动作废操作
		 **/
		public function getAllEffectiveOrders(){
			$this->invalidAllUnpaidOrder(72);	//先作废过期的订单
			/*状态不未作废，通过了审核时showstatus=1，其他showstatus=0*/
			$select_sql = "select id,username,startrow,startcol,rowcount,colcount,status>2 as showstatus,imageguid,href,introduction
				from ".DBPREFIX."order 
				where status>0 order by id asc";
			$rsEffectiveOrder = DB::get_all($select_sql);
			return $rsEffectiveOrder;
		}

		/*将若干小时内未支付的订单状态置成作废
		 *和提交订单时间postdate做比较
		 **/
		private function invalidAllUnpaidOrder($hours){
			$select_sql = "select id from ".DBPREFIX."order where payment<amount and adddate(now(),INTERVAL -".$hours." HOUR)>postdate and status=1";
			$rsUnpaidOrder = DB::get_all($select_sql);
			$ids = "";
			if (count($rsUnpaidOrder) > 0) {
				foreach ($rsUnpaidOrder as $keyUnpaidOrder => $valueUnpaidOrder) {
					$ids .= $valueUnpaidOrder["id"].",";
				}
			}
			$ids = rtrim($ids, ",");
			$ids != "" && $this->invalidOrderByIds($ids);
		}

		/*根据id串（,隔开）作废订单*/
		public function invalidOrderByIds($ids){
			return $this->changeOrderStatusById($ids, 0);
		}
		/*根据id串（,隔开）拒绝订单*/
		public function refuseOrderByIds($ids){
			$this->changeOrderStatusById($ids, 2);
		}
		/*根据id串（,隔开）通过订单*/
		public function passOrderByIds($ids){
			$this->changeOrderStatusById($ids, 3);
		}
		/*根据id串（,隔开）更改订单状态*/
		private function changeOrderStatusById($ids, $newStatus){
			$condition = "id in(".$ids.")";
			$dataArray = array("status"=>$newStatus);
			return DB::update(DBPREFIX."order", $dataArray, $condition);
		}

		/*获得所有已经被占用格子的信息
		 */
		public function getAllUsedGrid(){
			$select_sql = "select startcol,startrow,colcount,rowcount
				from ".DBPREFIX."order 
				where status>0";
			//所有格子，都可能会对计算造成影响的格子
			$rsAllRightBottomGrid = DB::get_all($select_sql);
			return $rsAllRightBottomGrid;
		}

		/*根据加入点的坐标和插入的宽高判断是否能够插入
		 */
		public function checkAddableRecByPoint($startcol, $startrow,$x=1, $y=1){
			/*$select_sql = "select startcol,startrow,colcount,rowcount
				from ".DBPREFIX."order 
				where status>0";
			//所有格子，都可能会对计算造成影响的格子
			$rsAllRightBottomGrid = DB::get_all($select_sql);*/
			$rsAllRightBottomGrid = $this->getAllUsedGrid();
			
			//只要一个到边就返回false
			if (COLCOUNT + 1 <= $startcol + $x - 1 || ROWCOUNT + 1 <= $startrow + $y - 1) {
				return false;
			}

			//如果和任意点有交集返回false
			$gridNeedCheck = array(
				"startcol"=>$startcol,
				"startrow"=>$startrow,
				"colcount"=>$x,
				"rowcount"=>$y
				);
			foreach ($rsAllRightBottomGrid as $key => $value) {
				if ($this->checkIsIntersectAboutTwoPoint($value, $gridNeedCheck)) {
					return false;
				}
			}
			return true;
		}

		/*判断两个格子是否相交
		 *$Grid=array(startcol,startrow,colcount,rowcount)
		 */
		private function checkIsIntersectAboutTwoPoint($Grid1, $Grid2){
			for ($col = $Grid1["startcol"] ; $col < $Grid1["startcol"] + $Grid1["colcount"] ; $col++) {
				for ($row = $Grid1["startrow"] ; $row < $Grid1["startrow"] + $Grid1["rowcount"] ; $row++) {
					if ($col >= $Grid2["startcol"]
						&& $col <= $Grid2["startcol"] + $Grid2["colcount"] - 1
						&& $row >= $Grid2["startrow"]
						&& $row <= $Grid2["startrow"] + $Grid2["rowcount"] - 1) {
						return true;
					}
				}
			}
			return false;
		}

		/*根据用户名和ip判断会员的资格和是否免费期间、价格
		 *$return(
		 *	authority	special/free/charge
		 *	freeperiod	0/1
		 *	price(
		 *		current		0
		 *		next		10
		 *	)
		 *	issoldout	0/1
		 *)	
		 */
		public function checkOrderSituation($username, $ipaddress){
			$return = array();

			//查看该用户的权限
			$select_authority = "select authority from ".DBPREFIX."member where username='".$username."'";
			$arrAuthority = DB::get_one($select_authority);
			if ($arrAuthority["authority"]) {
				$return["authority"] = "special";
				//如果是特殊权限用户，就可以返回了
				return $return;
			}

			//用户和ip是否还可以插入免费订单
			$select_ordercount = "select count(id) as count from ".DBPREFIX."order where ((username='".$username."' and username<>'') or postip='".$ipaddress."') and status>0";
			$arrOrderCount = DB::get_one($select_ordercount);
			if ($arrOrderCount["count"]) {
				//如果已有有效订单，返回收费；否则返回免费
				$return["authority"] = "charge";
			}else{
				$return["authority"] = "free";
			}

			//确定现已加入订单没有作废的格子的总数
			$select_gridcount = "select sum(rowcount*colcount) as count from ".DBPREFIX."order where status>0";
			$arrGridCount = DB::get_one($select_gridcount);
			$gridCount = $arrGridCount["count"] + 1;	//现加入的格子所在的总数
			if ($gridCount > ROWCOUNT*COLCOUNT) {
				$return["issoldout"] = 1;
				return $return;
			}else{
				$return["issoldout"] = 0;
			}
			
			//取得是否免费和价格(卖完肿么办)
			$select_price = "select price,endgrid from ".DBPREFIX."price where startgrid<=".$gridCount." and endgrid>=".$gridCount;
			$arrprice = DB::get_one($select_price);
			
			$return["price"]["current"] = $arrprice["price"];
			if($arrprice["price"] > 0){
				//不是免费期间
				$return["freeperiod"] = 0;
			}else{
				$gridCountNext = $arrprice["endgrid"] + 1;
				$select_nextprice = "select price from ".DBPREFIX."price where startgrid<=".$gridCountNext." and endgrid>=".$gridCountNext;
				$arrpriceNext = DB::get_one($select_nextprice);
				$return["freeperiod"] = 1;
				$return["price"]["next"] = $arrpriceNext["price"];
			}
			return $return;
		}

		/*根据数组插入新的订单
		 */
		public function insertOrderByArray($arrOrderForUpdate){
			return DB::insert(DBPREFIX."order", $arrOrderForUpdate);
		}

		/*根据用户名得到所有订单
		 */
		public function getAllOrderByUsername($username){
			$select_sql = "select *, 
			case when editdate='' or editdate is null then postdate else editdate END as orderdate
			from ".DBPREFIX."order where username='".$username."' order by orderdate desc";
			$rsAllOrder = DB::get_all($select_sql);
			return $rsAllOrder;
		}

		/*后台得到所有订单
		 */
		public function getAllOrder($notaudit = false){
			$where = "";
			if ($notaudit) {
				$where = " where status='1' ";
			}
			$select_sql = "select *, 
			case when editdate='' or editdate is null then postdate else editdate END as orderdate 
			from ".DBPREFIX."order ".$where." order by orderdate desc";
			$rsAllOrder = DB::get_all($select_sql);
			return $rsAllOrder;
		}

		/*根据id和用户名得到订单
		 */
		function getOneOrderById($orderId, $username){
			$select_sql = "select * from ".DBPREFIX."order where username='".$username."' and id='".$orderId."' and status>0";
			$rsOneOrder = DB::get_one($select_sql);
			return $rsOneOrder;
		}
		/*根据id得到订单
		 */
		function getOneOrderOnlyById($orderId){
			$select_sql = "select * from ".DBPREFIX."order where id='".$orderId."'";
			$rsOneOrder = DB::get_one($select_sql);
			return $rsOneOrder;
		}

		/*根据id和数据更新订单
		 */
		function updateOrderById($orderId, $arrNewArray){
			return DB::update(DBPREFIX."order", $arrNewArray, " id='".$orderId."'");
		}

		/*获得所有的img
		 */
		function getAllImg(){
			$dir = $_SERVER['DOCUMENT_ROOT']."/".PATH."gridimages/";
			$delArrImageGuid = array();
			$accesshandle = opendir($dir);
			while (false !== ($file = readdir($accesshandle))) {
				if($file != '.' && $file !== '..') {
					$delArrImageGuid[] = $file;
				}
			}
			return $delArrImageGuid;
		}

		/*获得所有无用的img
		 */
		function getAllEffectiveImg(){
			$select_sql = "select imageguid	from ".DBPREFIX."order";
			$rsAllOrder = DB::get_all($select_sql);
			$arrImageGuid = array();
			if (count($rsAllOrder) > 0) {
				foreach ($rsAllOrder as $keyAllOrder => $valueAllOrder) {
					$arrImageGuid[] = $valueAllOrder["imageguid"];
				}
			}
			//print_r($arrImageGuid);
			$dir = $_SERVER['DOCUMENT_ROOT']."/".PATH."gridimages/";
			$delArrImageGuid = array();
			$accesshandle = opendir($dir);
			while (false !== ($file = readdir($accesshandle))) {
				if($file != '.' && $file !== '..') {
					if (!in_array($file, $arrImageGuid)) {
						$delArrImageGuid[] = $file;
					}
				}
			}
			return $delArrImageGuid;
		}

		/*获得已被占用格子的个数
		 */
		function getUsefulGridCount(){
			$select_gridcount = "select sum(rowcount*colcount) as count from ".DBPREFIX."order where status>0";
			$arrGridCount = DB::get_one($select_gridcount);
			return $arrGridCount["count"];
		}

		/*根据已被占用格子的个数获得当前价格记录
		 */
		function getPriceItem(){
			$gridCount = $this->getUsefulGridCount() + 1;
			$select_price = "select * from ".DBPREFIX."price where startgrid<=".$gridCount." and endgrid>=".$gridCount;
			return DB::get_one($select_price);
		}

		/*根据表明得到所有备份记录
		 */
		function getAllRecords($table){
			$sqlAllRecords = "select * from ".DBPREFIX.$table;
			return DB::get_all($sqlAllRecords);
		}


	}
?>