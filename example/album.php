<html>
<head>
<title>Albums</title>
<style>
.thumbnail {
	width: 212px;
	min-height: 212px;
	float: left;
	padding-top: 5px;
	padding-left: 10px;
	padding-right: 10px;
	padding-bottom: 0px;
}
.thumbnail img {
	padding: 5px;
	margin: 0px;
	background: #f1f1f1;
	border-style: solid;
	border-width: 1px;
	border-color: #CCC;
}
.thumbnail .caption {
	font-size: 13px;
	color: #808080;
	padding: 3px 0px 0px 2px;
	margin: 0px;
	text-decoration: none;
	color: #000;
}
</style>
</head>
<body>
<?php

require('../vendor/autoload.php');

(new Dotenv\Dotenv(__DIR__))->load();

$b2AppKey   = $_ENV['B2_APP_KEY'];
$b2AppKeyId = $_ENV['B2_APP_KEY_ID'];
$b2BucketId = $_ENV['B2_BUCKET_ID'];
$cacheFolder = $_ENV['CACHE_FOLDER'];
$cdnPath = $_ENV['CDN_PATH'];

$client = new Kohenkatz\B2PhotoAlbum\Client($b2AppKey, $b2AppKeyId, $b2BucketId, $cacheFolder);

$album = $_GET['name'];

$images = $client->getAlbumImages($album);

?><div id="album-container"><?php

foreach ($images as $image) {
	echo <<<HERE
	<div class="thumbnail">
		<a href="$cdnPath/$album/$image" class="lightbox" rel="lightbox[this]"><img class="pwaimg" src="$cdnPath/_thumbs/$album/$image"></a>
	</div>
HERE;
}

?>
</div>
</body>
</html>
