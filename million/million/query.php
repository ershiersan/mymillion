<?php
    if ($_SESSION["ADMUSER"] != ADMUSER) {//没登录，跳到首页，保密地址
        header("Location:/");
    }else{
        require_once("secondnav.php");
        $common_area = $adNavigator."<span class='mainright'><span style='text-align:left;margin-left:60px;display:inline-block;'>";
        if ($_POST["type"]){/*提交查询*/
            $strsql = $_POST["strsql"];
            $strsql_arr=explode('--<br>--',$strsql);

            switch ($_POST["type"]) {
              case 'select':
                foreach ($strsql_arr as $sql_o)
                {
                    $common_area .= print_r(DB::get_all($sql_o), true)."<br>";
                }
                break;
              
              case 'update':
                # code...
                break;
              
              case 'insert':
                # code...
                break;
              
              case 'query':
                # code...
                break;
              
              default:
                # code...
                break;
            }
        }
        $common_area .= "<form action='index.php?a=query' method='post'>";
        $common_area .= "<select name='type'>
        <option value='select'>select</option>
        <option value='insert'>insert</option>
        <option value='update'>update</option>
        <option value='query'>query</option>
        <select><br><br>";
        $common_area .= "<textarea name='strsql' style='width:400px;height:200px;'></textarea><br><br><font color='red'>多条以“--".htmlspecialchars("<br>")."--”分隔</font><br><br>";
        $common_area .= "<input type='submit' value='提交' />";
        $common_area .= "<form>";
        $common_area .= "</span></span>";
    }
    
?>