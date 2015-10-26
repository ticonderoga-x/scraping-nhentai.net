<?php
// nhentai.net をスクレイピング
// URL直接指定で連番ファイルを保存
//http://nhentai.net/g/126799/
//http://i.nhentai.net/galleries/784025/406.jpg

// php nhentai.php [ギャラリーID] [終了ページ] [開始ページ（既定値=1）]

list($script, $out, $gallery, $end, $start, $suffix) = $argv;
if (!($start > 0)) $start = 1;

if (!$suffix) $suffix = "jpg";
$uriTemplate = 'http://i.nhentai.net/galleries/%s/%d.%s';
$fileNameTemplate = '%03d.%s';

$zipFile = sprintf("%s.zip", $out);

for ($n = $start; $n <= $end; $n++)
{
	$uri = sprintf($uriTemplate, $gallery, $n, $suffix);
	$bin = file_get_contents($uri);
	if (!$bin)
	{
		$suffix = ($suffix == "jpg" ? "png" : "jpg");
		$ufi = sprintf($uriTemplate, $gallery, $n, $suffix);
		$bin = file_get_contents($uri);
	}	
	printf("%s\n", $uri);
	
	$imgFileName = sprintf($fileNameTemplate, $n, $suffix);
	file_put_contents($imgFileName, $bin);
	exec( sprintf("zip %s %s", $zipFile, $imgFileName) );
	unlink($imgFileName);
}
