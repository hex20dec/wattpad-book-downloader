<b>Instructions:</b><br>
Go to the book you want to download<br>
Make sure you are on the first page of your book and NOT on <b><u>"Table of contens"</u></b><br>
Copy the URL/Link from the Address bar<br>
Paste it below and click the button.<br><br>
Please be patient, as this process may take up to 10 minutes to complete.
<br><br><br>
<form method="get" action=''>
The URL goes in this box:
    <input type="text" name="url">
    <input type="submit" value="I don't care if it's stealing, download it now!!!">
</form>
<br>

<?php
if(!empty($_GET['url'])){

$url = $_GET['url'];// "http://touch.wattpad.com/1095030-from-the-start-cancer-love-story-epilogue";
$url = preg_replace('/www/','touch',$url);
$numofpages = 1;

while($numofpages < 10000){
$site = file($url);
$allhtml = file_get_contents($url);
foreach($site as $key => $site_line){
  $entire2string .= $site_line;
  if($numofpages == 1){
if(preg_match('/div id=\"title/',$site_line,$titlematch)){
  $title = str_ireplace('<div id="title">','',$site_line);
  $title = str_ireplace('<\div>','',$title);
  $title = preg_replace('/\-.+/','',$title);
  $title = preg_replace('/^[\s\.]+/','',$title);
  $title = preg_replace('/[\s\.]+$/','',$title);
  $title = preg_replace('/[^\)\(a-zA-Z0-9\s]/','',$title);
  echo "Title: ".$title."<br><br>";
  if(!is_dir($title)){
  mkdir($title);
  }
}
}

if(preg_match_all("/a href=\"\/.+?\"/",$site_line,$matches)){
  foreach($matches[0] as $each_match){
  $allmatches[] = $each_match;
  }
}

}
if(!preg_match("/title=\"next/",$entire2string) && $numofpages == 1){
  die("<b>Error, please try again</b>");
}
//get the actual url of the next page
foreach($allmatches as $key => $clearmatches){
    $clearmatches_each[$key] = preg_replace("/a href=\"/",'',$clearmatches);
    $clearmatches_each[$key] = preg_replace("/\"$/",'',$clearmatches_each[$key]);
}

//$url = preg_replace("/a href=\"/",'',$allmatches[1]);
//$url = preg_replace("/\"$/",'',$url);
$url = $clearmatches_each[1];
$filename = preg_replace("/\//",'',$url);

$url = 'http://touch.wattpad.com'.$url;

//change the urls to numbers before putting it in file
   $nextnum = $numofpages+1;
   $previousnum = $numofpages-1;
if($numofpages == 1){
    $entire2string = str_ireplace($clearmatches_each[0],$nextnum.'.html"',$entire2string);
  $entire2string = str_ireplace($clearmatches_each[1],$nextnum.'.html"',$entire2string);
}elseif(count($allmatches) == 4){

  //previous
  $entire2string = str_ireplace($clearmatches_each[0],$previousnum.'.html"',$entire2string);
  $entire2string = str_ireplace($clearmatches_each[2],$previousnum.'.html"',$entire2string);
  //next
  $entire2string = str_ireplace($clearmatches_each[1],$nextnum.'.html',$entire2string);
  $entire2string = str_ireplace($clearmatches_each[3],$nextnum.'.html',$entire2string);
}else{
  $entire2string = str_ireplace($clearmatches_each[0],$previousnum.'.html"',$entire2string);
  $entire2string = str_ireplace($clearmatches_each[1],$previousnum.'.html"',$entire2string);
}


//save entire page to file
file_put_contents($title.'/'.$numofpages.'.html',$entire2string);
$entire2string = null;


//---------------------

//if this is the last page, break
if(count($allmatches) == 2 && $numofpages != 1){

    break;

}
$allmatches = null;
$numofpages++;

}
exec("rm download.tar.gz");
exec("tar -czf download.tar.gz '".$title."'");
exec("rm -r '".$title."'");
echo "<br><br><b>Done!</b>";
echo "<br>To download your offline book, click the link below:";
echo "<br><a href='download.tar.gz'>Better thank me every time you click me!</a>";
}


?>