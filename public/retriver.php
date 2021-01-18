<?php
require_once("../include/bittorrent.php");
dbconn();
loggedinorreturn();
if (get_user_class() < $updateextinfo_class) {
permissiondenied();
}
$id = intval($_GET["id"] ?? 0);
$type = intval($_GET["type"] ?? 0);
$siteid = $_GET["siteid"] ?? 0; // 1 for IMDb

if (!isset($id) || !$id || !is_numeric($id) || !isset($type) || !$type || !is_numeric($type) || !isset($siteid) || !$siteid)
die();

$r = sql_query("SELECT id, url, pt_gen from torrents WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
if(mysql_num_rows($r) != 1)
die();

$row = mysql_fetch_assoc($r);

switch ($siteid)
{
	case 1 : 
	{
		$imdb_id = parse_imdb_id($row["url"]);
		if ($imdb_id)
		{
			$thenumbers = $imdb_id;
			$imdb = new \Nexus\Imdb\Imdb();
			$movie = $imdb->getMovie($imdb_id);
			$movieid = $thenumbers;
			$target = array('Title', 'Credits', 'Plot');
			($type == 2 ? $imdb->purgeSingle($imdb_id) : "");
			set_cachetimestamp($id,"cache_stamp");
			$Cache->delete_value('imdb_id_'.$thenumbers.'_movie_name');
			$Cache->delete_value('imdb_id_'.$thenumbers.'_large', true);
			$Cache->delete_value('imdb_id_'.$thenumbers.'_median', true);
			$Cache->delete_value('imdb_id_'.$thenumbers.'_minor', true);
			header("Location: " . get_protocol_prefix() . "$BASEURL/details.php?id=".htmlspecialchars($id));
		}
		break;
	}
	case \Nexus\PTGen\PTGen::SITE_IMDB:
	case \Nexus\PTGen\PTGen::SITE_DOUBAN:
	case \Nexus\PTGen\PTGen::SITE_BANGUMI:
		{
			$ptGenInfo = json_decode($row['pt_gen'], true);
			$link = $ptGenInfo[$siteid]['link'];
			$ptGen = new \Nexus\PTGen\PTGen();
			$result = $ptGen->generate($link, true);
			$ptGenInfo[$siteid]['data'] = $result;
			sql_query(sprintf("update torrents set pt_gen = %s where id = %s", sqlesc(json_encode($ptGenInfo)), $id))  or sqlerr(__FILE__, __LINE__);
			header("Location: " . get_protocol_prefix() . "$BASEURL/details.php?id=".htmlspecialchars($id));
			break;
		}
	default :
	{
		die("Error!");
		break;
	}
}

?>
