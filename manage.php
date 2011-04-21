<html>
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>Local metachan. Управление тредами.</title>
</head>
<body>
<?php
require 'webloc.inc';
$list_of_alias=loalias($file_alias);
$links = file($file_links,FILE_IGNORE_NEW_LINES);


$uploaddir = 'arch/';
if ($_FILES['userfile']['type'] == "application/x-gzip")
 {
  if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir . $_FILES['userfile']['name'])) 
   {
    exec($extract_cl."arch/".$_FILES['userfile']['name'],$output);
   };
 };

///////////////////////////////////////////////
// Добавление треда.
$am = "Введите Url";
if (isset($_POST['add_thread']))
 {
  if ($_POST['thread'] != "")
   {
    if (file_exists($file_lock))
     {
      $am = "Файл ссылок занят другим процессом, попробуйте позже.";
     }
    else
     {
      if (! in_array($_POST['thread'],$links))
       {
        $fd0 = fopen($file_links,"a+");
        fwrite($fd0,"\n".$_POST['thread']);
        fclose($fd0);
        $am = "<p class=\"stat\">Тред отправлен в обработку<p>";
        $_POST['thread']="";
       }
      else
       {
        $am= "<p class=\"stat\">Тред уже добавлен<p>";
       };
     };
   };
 };
echo $am;
///////////////////////////////////////////////
//Удаление треда.
if (isset($_POST['del_thread']))
 {
  if (! empty($_POST['threadlist']))
   {
    $asd=array_keys($_POST['threadlist']);
    $_POST['threadlist']="";
    if (! file_exists($file_lock))
     {
      foreach ($asd as $sk)
       {
        $cc="python grab.py del threads/".$sk;
        exec($cc,$output);
        echo ("<br>".$output[0]."  ".$sk."  ");
        $df = explode("_",$sk);
        $df_len = sizeof($df);
        switch ($df[0]) 
         {
          case "tirech"     : $s1="2-ch.ru"; break;
          case "0chan"      : $s1="www.0chan.ru"; break;
          case "iichan"     : $s1="iichan.ru"; break;
          case "uchan"      : $s1="uchan.org.ua"; break;
          case "longtirech" : $s1="2--ch.ru"; break;
          case "pirach"     : $s1="2ch.so";break;
         };
        if ($df_len == 3)
         {
          $del_thr[] = "http://".$s1."/".$df[1]."/res/".$df[2];
         }
        else
         {
          $del_thr[] = "http://".$s1."/_".$df[2]."/res/".$df[3];
         };
       };
      $dt = array_diff($links,$del_thr);
      $fd = fopen($file_links,"w+");
      foreach ($dt as $tt)
       {
        fwrite($fd,$tt."\n");
       };
      fclose($fd);
     }
    else
     {
      echo("<br> Файл ссылок занят другим процессом, попробуйте позже.");
     };
   };
 };
///////////////////////////////////////////////
// Архивация тредов.
if (isset($_POST['archive_thread']))
 {
  if (! empty($_POST['threadlist']))
   {
    $asd=array_keys($_POST['threadlist']);
    $_POST['threadlist']="";
    foreach ($asd as $sk)
     {
      $cc="python grab.py e threads/".$sk;
      exec($cc,$output);
     };
   };
 };
///////////////////////////////////////////////
// Изменение алиасов.
if (isset($_POST['apply_alias']))
 {
  $fg=$_POST['aliaslist'];
  $asd=array_keys($_POST['aliaslist']);
  $fd1=fopen($file_alias,"w+");
  foreach ($asd as $sk)
   {
    if (($fg[$sk] !== "") && ($fg[$sk] !== $list_of_alias[$sk]))
     {
      $list_of_alias[$sk] = $fg[$sk]."\n";
     };
    if (!empty($list_of_alias[$sk]))
     {
      fwrite($fd1,$sk." ".$list_of_alias[$sk]);
     };
   };
  fclose($fd1);
  $_POST['aliaslist']="";
 };
///////////////////////////////////////////////
//Удаление архива
if (isset($_POST['del_arch']))
 {
  if (! empty($_POST['archlist']))
   {
    $asd=array_keys($_POST['archlist']);
    foreach ($asd as $sk)
     {
      exec("rm arch/".$sk,$output);
     };
   };
 };
///////////////////////////////////////////////
//Развертывание архива
if (isset($_POST['extract_arch']))
 {
  if (! empty($_POST['archlist']))
   {
    $asd=array_keys($_POST['archlist']);
    foreach ($asd as $sk)
     {
      exec($extract_cl."arch/".$sk,$output);
      print_r($output);
     };
   };
 };
///////////////////////////////////////////////
?>
<form action="manage.php" method="post">
<input type="text" size=35 name="thread"><br>
<input type="submit" name="add_thread" value="Добавить">
</form>
<hr>
<form action="manage.php" method="post">
<?
$files=lfdir('threads','html');
/////////////////////////////////////////////////
//Вывод списка тредов для изменения алиасов и списка для удаления.
foreach ($files as $as) 
 {
  if (! empty($list_of_alias[$as]))
   {
    $name = $list_of_alias[$as];
   }
  else
   {
    $name = $as;
   };
  if (isset($_POST['alias_thread']))
   {
    echo $name." <input type=text size=35 name=\"aliaslist[".$as."]\"><br>";
   }
  else
   {
    echo "<input type=checkbox name=threadlist[".$as."] value=\"1\">".$name."<br>";
   };
 };
if (isset($_POST['alias_thread'])) 
 {
  echo "<input type=\"submit\" name=\"apply_alias\" value=\"Применить\">";
 }
else
 {
  echo "<input type=\"submit\" name=\"del_thread\" value=\"Удалить\">
  <input type=\"submit\" name=\"alias_thread\" value=\"Управление именами\">
  <input type=\"submit\" name=\"archive_thread\" value=\"Архивировать\">";
 };
/////////////////////////////////////////////////
?>
</form>
<?
$arch_files=lfdir('arch','tar.gz');
if ( $arch_files[0] != "")
 {
echo("<hr>
      Доступные архивы:
      <form action=\"manage.php\" method=\"post\">");
  foreach ($arch_files as $as) 
   {
    echo("<input type=checkbox name=archlist[".$as."] value=1><a href=\"arch/".$as." \">".$as."</a><br>");
   };
echo("<input type=\"submit\" name=\"del_arch\" value=\"Удалить Архив\">
      <input type=\"submit\" name=\"extract_arch\" value=\"Развернуть Архив\">
     </form>");
 };
?>
<hr>
<form enctype="multipart/form-data" action="manage.php" method="post">
Загрузить архив: <input name="userfile" type="file">
<input type="submit" value="Загрузить">
</form>

<hr>
<a href="./">Вернуться к списку тредов.</a>
</body>
</html>