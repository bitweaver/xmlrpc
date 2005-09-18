<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_xmlrpc/send_objects.php,v 1.1.1.1.2.4 2005/09/18 04:23:08 wolff_borg Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: send_objects.php,v 1.1.1.1.2.4 2005/09/18 04:23:08 wolff_borg Exp $
 * @package xmlrpc
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
require_once( UTIL_PKG_PATH.'xmlrpc/xmlrpc.inc' );
require_once( UTIL_PKG_PATH.'xmlrpc/xmlrpcs.inc' );
if ($gBitSystem->isPackageActive( 'articles' )) {
	require_once( ARTICLES_PKG_PATH.'art_lib.php' );
}
if ($gBitSystem->isPackageActive( 'wiki' )) {
	require_once( WIKI_PKG_PATH.'BitPage.php' );
}
if (!$gBitSystem->isFeatureActive( 'feature_comm' )) {
	die;
}
if (!$gBitUser->hasPermission( 'bit_p_send_pages' ) && !$gBitUser->hasPermission( 'bit_p_send_articles' )) {
	$gBitSmarty->assign('msg', tra("You dont have permission to use this feature"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
if (!isset($_REQUEST["username"])) {
	$_REQUEST["username"] = $gBitSystem->getPreference("xmlrpc_username", $gBitUser->mUsername);
} else {
	$gBitSystem->storePreference("xmlrpc_username", $_REQUEST["username"]);
}
if (!isset($_REQUEST["path"])) {
	$_REQUEST["path"] =  $gBitSystem->getPreference("xmlrpc_path", XMLRPC_PKG_URL.'commxmlrpc.php');
} else {
	$gBitSystem->storePreference("xmlrpc_path", $_REQUEST["path"]);
}
if (!isset($_REQUEST["site"])) {
	$_REQUEST["site"] =  $gBitSystem->getPreference("xmlrpc_site", '');
} else {
	$gBitSystem->storePreference("xmlrpc_site", $_REQUEST["site"]);
}
if (!isset($_REQUEST["password"])) {
	$_REQUEST["password"] = '';
}
if (!isset($_REQUEST["sendpages"])) {
	$sendpages = array();
} else {
	$sendpages = unserialize(urldecode($_REQUEST['sendpages']));
}
if (!isset($_REQUEST["sendarticles"])) {
	$sendarticles = array();
} else {
	$sendarticles = unserialize(urldecode($_REQUEST['sendarticles']));
}
$gBitSmarty->assign('username', $_REQUEST["username"]);
$gBitSmarty->assign('site', $_REQUEST["site"]);
$gBitSmarty->assign('path', $_REQUEST["path"]);
$gBitSmarty->assign('password', $_REQUEST["password"]);
if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$gBitSmarty->assign('find', $find);
if (isset($_REQUEST["addpage"])) {
	if (!in_array($_REQUEST["page_name"], $sendpages)) {
		$sendpages[] = $_REQUEST["page_name"];
	}
}
if (isset($_REQUEST["clearpages"])) {
	$sendpages = array();
}
if (isset($_REQUEST["addarticle"])) {
	if (!in_array($_REQUEST["article_id"], $sendarticles)) {
		$sendarticles[] = $_REQUEST["article_id"];
	}
}
if (isset($_REQUEST["cleararticles"])) {
	$sendarticles = array();
}
$msg = '';
if (isset($_REQUEST["send"])) {
	
	// Create XMLRPC object
	$client = new xmlrpc_client($_REQUEST["path"], $_REQUEST["site"], 80);
	$client->setDebug(0);
	foreach ($sendpages as $page) {
		$page_info = $wikilib->get_page_info($page);
		if ($page_info) {
			$searchMsg = new xmlrpcmsg('sendPage', array(
				new xmlrpcval($_SERVER["SERVER_NAME"], "string"),
				new xmlrpcval($_REQUEST["username"], "string"),
				new xmlrpcval($_REQUEST["password"], "string"),
				new xmlrpcval($page, "string"),
				new xmlrpcval(base64_encode($page_info["data"]), "string"),
				new xmlrpcval($page_info["comment"], "string"),
				new xmlrpcval($page_info["description"], "string")
			));
			$result = $client->send($searchMsg);
			if (!$result) {
				$errorMsg = 'Cannot login to server maybe the server is down';
			} else {
				if (!$result->faultCode()) {
					// We have a response
					$res = php_xmlrpc_decode($result->value());
					if ($res) {
						$msg .= tra('page'). ': ' . $page . tra(' successfully sent'). "<br/>";
					}
				} else {
					$errorMsg = $result->faultstring();
					$msg .= tra('page'). ': ' . $page . tra(' not sent').': '. tra($errorMsg) . "<br/>";
				}
			}
		}
	}
	foreach ($sendarticles as $article) {
		$page_info = $artlib->get_article($article);
		if ($page_info) {
			$searchMsg = new xmlrpcmsg('sendArticle', array(
				new xmlrpcval($_SERVER["SERVER_NAME"], "string"),
				new xmlrpcval($_REQUEST["username"], "string"),
				new xmlrpcval($_REQUEST["password"], "string"),
				new xmlrpcval(base64_encode($page_info["title"]), "string"),
				new xmlrpcval(base64_encode($page_info["author_name"]), "string"),
				new xmlrpcval($page_info["size"], "int"),
				new xmlrpcval($page_info["use_image"], "string"),
				new xmlrpcval($page_info["image_name"], "string"),
				new xmlrpcval($page_info["image_type"], "string"),
				new xmlrpcval($page_info["image_size"], "int"),
				new xmlrpcval($page_info["image_x"], "int"),
				new xmlrpcval($page_info["image_x"], "int"),
				new xmlrpcval(base64_encode($page_info["image_data"]), "string"),
				new xmlrpcval($page_info["publish_date"], "int"),
				new xmlrpcval($page_info["created"], "int"),
				new xmlrpcval(base64_encode($page_info["heading"]), "string"),
				new xmlrpcval(base64_encode($page_info["body"]), "string"),
				new xmlrpcval($page_info["hash"], "string"),
				new xmlrpcval($page_info["author"], "string"),
				new xmlrpcval($page_info["type"], "string"),
				new xmlrpcval($page_info["rating"], "string")
			));
			$result = $client->send($searchMsg);
			if (!$result) {
				$errorMsg = 'Cannot login to server maybe the server is down';
			} else {
				if (!$result->faultCode()) {
					// We have a response
					$res = php_xmlrpc_decode($result->value());
					if ($res) {
						$msg .= tra('article'). ': ' . $article . tra(' successfully sent'). "<br/>";
					}
				} else {
					$errorMsg = $result->faultstring();
					$msg .= tra('page'). ': ' . $article . tra(' not sent'). ': '.tra($errorMsg) . "<br/>";
				}
			}
		}
	}
}
$gBitSmarty->assign('msg', $msg);
$gBitSmarty->assign('sendpages', $sendpages);
$gBitSmarty->assign('sendarticles', $sendarticles);
$form_sendpages = urlencode(serialize($sendpages));
$form_sendarticles = urlencode(serialize($sendarticles));
$gBitSmarty->assign('form_sendarticles', $form_sendarticles);
$gBitSmarty->assign('form_sendpages', $form_sendpages);
if ($gBitSystem->isPackageActive( 'wiki' )) {
	$pages = $wikilib->list_pages(0, -1, 'page_name_asc', $find);
	$gBitSmarty->assign_by_ref('pages', $pages["data"]);
}
if ($gBitSystem->isPackageActive( 'articles' )) {
	$articles = $artlib->list_articles(0, -1, 'publish_date_desc', $find, $gBitSystem->getUTCTime(), $user);
	$gBitSmarty->assign_by_ref('articles', $articles["data"]);
}

// Display the template
$gBitSystem->display( 'bitpackage:xmlrpc/send_objects.tpl');
?>
