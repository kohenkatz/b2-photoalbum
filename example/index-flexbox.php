<?php

?>
<html>
<head>
<title>Albums</title>
<style>
#album-container {
  display: flex;
  flex-wrap: wrap;
  align-content: flex-start;
  justify-content: space-between;
}

#album-container .thumbnail {
  flex-grow: 1;
  flex-basis: 200px;
  max-width: 300px;
  margin: 5px;
}

#album-container img {
  height: 100%;
  width: 100%;
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

$albums = $client->getAlbums();

?><div id="album-container"><?php

foreach ($albums as $album) {
	echo <<<HERE
	<div class="thumbnail">
		<img src="$cdnPath/_thumbs/$album.jpg">
		<a href="album.php?name=$album">Summer $album highlights</a>
	</div>\n
HERE;
}

?>
<div class="thumbnail"></div><div class="thumbnail"></div><div class="thumbnail"></div><div class="thumbnail"></div><div class="thumbnail"></div><div class="thumbnail"></div><div class="thumbnail"></div><div class="thumbnail"></div><div class="thumbnail"></div><div class="thumbnail"></div><div class="thumbnail"></div>
</div>
</body>
</html>
