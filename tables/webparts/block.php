<? function block($name,$title,$img=NULL) { 
	if (empty($img))
		$img = "style/img/" . $name . ".png";
	if (preg_match("/\//",$img)===0)
		$img = "style/img/" . $img  . ".png";
	
	/*
	if (strpos($img,"http://") === 0)
	{	//extract subdomainname
		$img = explode("//",$img);
		$img = explode("." ,$img[1]);
		$img = $img[0];
	}
	*/
	if (!preg_match("/^http/i",$name))
	if (!preg_match("/\.[a-z0-9]{1,4}/i",$name)) $name .= ".php";
?>
<? if (preg_match("/\.(png|jpg|gif)$/i",$img)) { ?>
               <div class="product">
		<a href="<?= $name ?>">
                <h4><?= $title ?></h4>
		<img style="width: 190px; height: 160px;" src="<?= $img ?>" />
		</a>
              </div>
<? } else if (preg_match("/^http/i",$img)) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $img);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
   } else {
	print "<!--#include virtual=\"" . $img . "\"  -->\n";
   }
} ?>

