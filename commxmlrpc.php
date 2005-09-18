<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_xmlrpc/commxmlrpc.php,v 1.1.1.1.2.3 2005/09/18 04:23:08 wolff_borg Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: commxmlrpc.php,v 1.1.1.1.2.3 2005/09/18 04:23:08 wolff_borg Exp $
 * @package xmlrpc
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( KERNEL_PKG_PATH.'comm_lib.php' );
require_once( UTIL_PKG_PATH.'xmlrpc/xmlrpc.inc' );
require_once( UTIL_PKG_PATH.'xmlrpc/xmlrpcs.inc' );
if (!$gBitSystem->isFeatureActive("feature_comm")) {
	die;
}
$map = array(
	"sendPage" => array("function" => "sendPage"),
	"sendArticle" => array("function" => "sendArticle")
);
$s = new xmlrpc_server($map);
/* Validates the user and returns user information */
function sendPage($params) {
	// Get the page and store it in received_pages
	global $gBitSystem, $gBitUser, $commlib;
	$pp = $params->getParam(0);
	$site = $pp->scalarval();
	$pp = $params->getParam(1);
	$username = $pp->scalarval();
	$pp = $params->getParam(2);
	$password = $pp->scalarval();
	$pp = $params->getParam(3);
	$page_name = $pp->scalarval();
	$pp = $params->getParam(4);
	$data = $pp->scalarval();
	$pp = $params->getParam(5);
	$comment = $pp->scalarval();
	$pp = $params->getParam(6);
	$description = $pp->scalarval();
	//
	if (!$gBitUser->validate($username, $password, '', '')) {
		return new xmlrpcresp(0, 101, "Invalid username or password");
	}
	// Verify if the user has bit_p_sendme_pages
	if (!$gBitUser->user_has_permission($username, 'bit_p_sendme_pages')) {
		return new xmlrpcresp(0, 101, "Permissions denied user $username cannot send pages to this site");
	}
	// Store the page in the tiki_received_pages_table
	$data = base64_decode($data);
	$commlib->receive_page($page_name, $data, $comment, $site, $username, $description);
	return new xmlrpcresp(new xmlrpcval(1, "boolean"));
}
function sendArticle($params) {
	// Get the page and store it in received_pages
	global $gBitSystem, $gBitUser, $commlib;
	$pp = $params->getParam(0);
	$site = $pp->scalarval();
	$pp = $params->getParam(1);
	$username = $pp->scalarval();
	$pp = $params->getParam(2);
	$password = $pp->scalarval();
	$pp = $params->getParam(3);
	$title = $pp->scalarval();
	$pp = $params->getParam(4);
	$author_name = $pp->scalarval();
	$pp = $params->getParam(5);
	$size = $pp->scalarval();
	$pp = $params->getParam(6);
	$use_image = $pp->scalarval();
	$pp = $params->getParam(7);
	$image_name = $pp->scalarval();
	$pp = $params->getParam(8);
	$image_type = $pp->scalarval();
	$pp = $params->getParam(9);
	$image_size = $pp->scalarval();
	$pp = $params->getParam(10);
	$image_x = $pp->scalarval();
	$pp = $params->getParam(11);
	$image_y = $pp->scalarval();
	$pp = $params->getParam(12);
	$image_data = $pp->scalarval();
	$pp = $params->getParam(13);
	$publish_date = $pp->scalarval();
	$pp = $params->getParam(14);
	$expire_date = $pp->scalarval();
	$pp = $params->getParam(15);
	$created = $pp->scalarval();
	$pp = $params->getParam(16);
	$heading = $pp->scalarval();
	$pp = $params->getParam(17);
	$body = $pp->scalarval();
	$pp = $params->getParam(18);
	$hash = $pp->scalarval();
	$pp = $params->getParam(19);
	$author = $pp->scalarval();
	$pp = $params->getParam(20);
	$type = $pp->scalarval();
	$pp = $params->getParam(21);
	$rating = $pp->scalarval();
	//
	if (!$gBitUser->validate($username, $password, '', '')) {
		return new xmlrpcresp(0, 101, "Invalid username or password");
	}
	// Verify if the user has bit_p_sendme_pages
	if (!$gBitUser->user_has_permission($username, 'bit_p_sendme_articles')) {
		return new xmlrpcresp(0, 101, "Permissions denied user $username cannot send articles to this site");
	}
	// Store the page in the tiki_received_pages_table
	$title = base64_decode($title);
	$author_name = base64_decode($author_name);
	$image_data = base64_decode($image_data);
	$heading = base64_decode($heading);
	$body = base64_decode($body);
	$commlib->receive_article($site, $username, $title, $author_name, $size, $use_image, $image_name, $image_type, $image_size,
		$image_x, $image_y, $image_data, $publish_date, $expire_date, $created, $heading, $body, $hash, $author, $type, $rating);
	return new xmlrpcresp(new xmlrpcval(1, "boolean"));
}
?>
