<?php
session_start();
$authnum=random(4);//验证码字符.
$_SESSION['code']=$authnum;
//生成验证码图片
Header("Content-type: image/PNG");
$im = imagecreate(55,18); //imagecreate() 新建图像，大小为 x_size 和 y_size 的空白图像。
$red = ImageColorAllocate($im, 153,51,0); //设置背景颜色
$white = ImageColorAllocate($im, 255,204,0);//设置文字颜色
$gray = ImageColorAllocate($im, 102,102,0); //设置杂点颜色
imagefill($im,55,18,$red);

for ($i = 0; $i < strlen($authnum); $i++)
{
// $i%2 == 0?$top = -1:$top = 3;

imagestring($im, 6, 13*$i+4, 1, substr($authnum,$i,1), $white);
//int imagestring ( resource image, int font, int x, int y, string s, int col)
//imagestring() 用 col 颜色将字符串" title="字符串" >字符串 s 画到 image 所代表的图像的 x，y 座标处（图像的左上角为 0, 0）。如果 font 是 1，2，3，4 或 5，则使用内置字体。
}
for($i=0;$i<150;$i++) //加入干扰象素
{
imagesetpixel($im, rand()%55 , rand()%18 , $gray);
//int imagesetpixel ( resource image, int x, int y, int color)
//imagesetpixel() 在 image 图像中用 color 颜色在 x, y 坐标（图像左上角为 0, 0）上画一个点。
}
ImagePNG($im); //以 PNG 格式将图像输出到浏览器或文件
ImageDestroy($im);//销毁一图像

//产生随机数函数
function random($length) {
$hash = '';
$chars = 'ABCDEFGHJKLMNOPQRSTUVWXYZ023456789abcdefghijklmnopqrstuvwxyz';

$max = strlen($chars) - 1;

for($i = 0; $i < $length; $i++) {
$hash .= $chars[mt_rand(0, $max)];
}
return $hash;

}
?>