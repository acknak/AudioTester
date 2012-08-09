<?
/*
 * post mode
 */
if(count(($dat=$_POST)) > 0){
  $f = "data.csv";
  if(!($fp = @fopen($_SERVER['DOCUMENT_ROOT'].'/'.$f, 'r+')))
    $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/'.$f, 'w+');
  $tmp_hd = array();
  while(!feof($fp)){
    $tmp = split(',', trim(fgets($fp)));
    if(array_shift($tmp) === "head")
      $tmp_hd = $tmp;
  }
  ksort($dat);
  flock($fp, LOCK_EX);
  if(array_keys($dat) !== $tmp_hd)
    fwrite($fp, "head,".implode(',', array_keys($dat))."\n");
  fwrite($fp, "body,".implode(',', array_values($dat))."\n");
  flock($fp, LOCK_UN);
  fclose($fp);
  exit("送信しました");
}

/*
 * input mode
 */
$d = "./data/";
$h = opendir($d);
if(!$h)
  exit("data not found");
$flist = array();
while(($f=readdir($h)) !== false)
  if(is_dir($d.$f) && !preg_match("/^\.+$/", $f))
    $flist[] = $f;
closedir($h);
?>
<!DOCTYPE html>
<title>ML-PSOLA</title>
<style>fieldset{margin:20px 0}ul{list-style-type:none}</style>
<h1>ML-PSOLA</h1>
<form action="" method="post">
<?php
for($i=0;$i<count($flist);$i++){
echo <<< HEAD_END
<fieldset>
<legend>{$flist[$i]}</legend>
<ul>
HEAD_END;
$h_ = opendir($d.$flist[$i]);
$flist_ = array();
while(($f_=readdir($h_))!==false) if(!is_dir($d.$f.$f_)) $flist_[]=$f_;
closedir($h_);
shuffle($flist_);
for($j=0;$j<count($flist_);$j++){
echo <<< ELEMENT_END
<li>
<audio src="$d$flist[$i]/$flist_[$j]" controls></audio>
1<input type="range" name="$flist[$i].$flist_[$j]" min="1" max="7" value="4">7
</li>
ELEMENT_END;
}
echo <<< FOOT_END
</ul>
</fieldset>
FOOT_END;
}
?>
<input type="submit" value="submit">
</form>
