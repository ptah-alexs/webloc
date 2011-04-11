<?php
///////////////////////////////////////////////
//Файловые переменные
$file_lock="lock.pid";
$file_links="links.txt";
$file_alias="alias";

///////////////////////////////////////////////
//Получение списка файлов в каталоге по маске.
function lfdir($dirname,$filter)
{
 if (file_exists($dirname))
  {
   $files=scandir($dirname);
   foreach ($files as $as) 
    {
     if (strpos($as,".".$filter)!="")
      {
       $res[]=$as;
      };
    };
  }
 else $res[]="";
 return $res;
};

///////////////////////////////////////////////
//Получение списка алиасов.
function loalias($f_a)
{
 $aliases = file($f_a);
 foreach ($aliases as $alias)
  {
   $alias_tmp = explode(" ",$alias, 2);
   $res[$alias_tmp[0]] = $alias_tmp[1];
  };
 return $res;
};
?>