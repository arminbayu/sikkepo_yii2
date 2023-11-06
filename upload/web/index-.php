<?php
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	header('Location: '.$uri.'/dashboard');
	exit;
?>
Something is wrong with the XAMPP installation :-(



<!--<!doctype html>
<head>

</head>
<body>
<title></title>

<iframe width="1000" height="1000" src="img/ofline.png" title="infromasi" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>


</body>
</html>-->



