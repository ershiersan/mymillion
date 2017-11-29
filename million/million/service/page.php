<?php
	function ShowPageGuide($current_page, $page_rows,$total_lines,$href)
	{
		$return = "";
		$page_count = ceil($total_lines/$page_rows);				//总页数
		$return .= '<table class="showPage" width="99%" cellspacing="0"><tr><td>';
		$return .= "<span style=\"font-size:12px\">共 $page_count 页/$total_lines  条记录&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
		if( $current_page > 0)
		{
			$return .= "<a href=\"".$href."&page=0\" class=\"page_off\">首页</a>";
			$return .= "<a href=\"".$href."&page=". ($current_page-1) ."\"  class=\"page_off\">上一页</a>";
		}
		
		// 前面显示 3 页
		$html_before = "";
		for( $i = 1; $i <=3; $i++ ) 
		{
			if( $current_page- $i >=0 )
				$html_before = "<a href=\"".$href."&page=". ($current_page-$i) ."\"  class=\"page_off\">".($current_page-$i+1)."</a>" .$html_before;
			else
				break;
		}
		$return .= $html_before;
		
		// 显示当前页,如果没有数据记录则不显示
		if($total_lines != 0)
			$return .= "<span class=\"page_on\">". ($current_page+1) ."</span>";
		
		//后面显示3页
		$html_after = "";
		for( $j=1; $j<=7-$i; $j++ )
		{
			if( $current_page + $j < $page_count )
				$html_after .= "<a href=\"".$href."&page=". ($current_page+$j) ."\"  class=\"page_off\">".($current_page+$j+1)."</a>";
			else
				break;
		}
		$return .= $html_after;
		
		if( $current_page < $page_count -1 )
		{
			$return .= "<a href=\"".$href."&page=".($current_page+1)."\"  class=\"page_off\">下一页</a>";
			$return .= "<a href=\"".$href."&page=".($page_count-1)."\" class=\"page_off\">末页</a>";
		}
		
		$return .= '</tr></table>';
		return $return;
	}
?>
