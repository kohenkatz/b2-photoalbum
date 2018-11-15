<?php

namespace Kohenkatz\B2PhotoAlbum;

use BackblazeB2\Client as B2Client;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;

class Client
{
	private $b2Client;

	private $bucketId;

	private $cache;

	public function __construct($applicationKey, $applicationKeyId, $bucketId, $cachePath)
	{
		$this->cache = new Repository(new FileStore(new Filesystem(), $cachePath));

		$this->bucketId = $bucketId;

		$this->b2Client = new B2Client($applicationKeyId, $applicationKey);
	}

	public function getAlbums()
	{
		return $this->cache->remember('b2_albums', 60 * 24, function() {
			$files = $this->b2Client->listFiles([
				'BucketId' => $this->bucketId,
				'Delimiter' => '/'
			]);

			$files = array_filter($files, function ($file) {
				$name = $file->getName();
				return $name[0] !== '_';
			});

			return array_map(function ($file) {
				return rtrim($file->getName(), '/');
			}, $files);
		});
	}

	public function getAlbumImages($albumPath, $fileExts = ['.jpg'])
	{
		return $this->cache->remember('b2_album_images_'.$albumPath.'_type_'.implode('', $fileExts), 60 * 24, function () use ($albumPath, $fileExts) {
			$files = $this->b2Client->listFiles([
				'BucketId' => $this->bucketId,
				'Delimiter' => '/',
				'Prefix' => $albumPath . '/'
			]);

			$files = array_filter($files, function ($file) use ($fileExts) {
				$name = $file->getName();
				return in_array(substr($name, -4), $fileExts) || in_array(substr($name, -5), $fileExts);
			});

			return array_map(function ($file) use ($albumPath) {
				// Strip off the album path
				return substr($file->getName(), strlen($albumPath) + 1);
			}, $files);
		});
	}
}
