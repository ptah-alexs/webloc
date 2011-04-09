<html>
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>Local metachan.</title>
</head>
<body>
<ul type="disk">
<?
require 'webloc.inc';
$list_of_alias=loalias($file_alias);
$links = file($file_links,FILE_IGNORE_NEW_LINES);
foreach ($links as $link)
 {
  $s0=explode("/",substr($link,7,strlen($link)),4);
  switch ($s0[0])
   {
    case "2-ch.ru"      : $s1 = "tirech";break;
    case "www.0chan.ru" : $s1 = "0chan";break;
    case "iichan.ru"    : $s1 = "iichan";break;
    case "ucan.org.ua"  : $s1 = "uchan";break;
    case "2--ch.ru"     : $s1 = "longtirech";break;
    case "2ch.so"       : $s1 = "pirach";break;
   };
  $list_of_links[]=$s1."_".$s0[1]."_".$s0[3];
 };

$files=lfdir('threads','html');
$ind=0;
foreach ($files as $f)
 {
  $li_type="";
  $a_class="";
  $died="";
  if (! in_array($f,$list_of_links))
   {
    $li_type=" type=\"circle\"";
    $a_class=" class=\"died\"";
    $died=" (тред умер)";
   };
  if ($list_of_alias[$f] != "")
   {
    $name = $list_of_alias[$f];
   }
  else
   {
    $name = $f;
   };
  echo "<li".$li_type."><a href=\"threads/".$f."\"".$a_class.">".$name.$died."</a></li><br>";
  $ind=$ind+1;;
 };
?>
</ul>
<hr>
<p class="stat">Number of threads in links.txt: <? echo(sizeof($links)); ?>, in this index: <? echo($ind); ?>  </p>
<p class="stat"><a href="manage.php">Управление тредами</a></p>
</body>
</html>