<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_xmlrpc/xmlrpc_services.php,v 1.2.2.5 2005/09/18 04:23:08 wolff_borg Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: xmlrpc_services.php,v 1.2.2.5 2005/09/18 04:23:08 wolff_borg Exp $
 * @package xmlrpc
 * @subpackage function_services
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
require_once( UTIL_PKG_PATH.'xmlrpc/xmlrpc.inc' );
require_once( UTIL_PKG_PATH.'xmlrpc/xmlrpcs.inc' );
if ($gBitSystem->isPackageActive( 'blogs' )) {
	include_once( BLOGS_PKG_PATH.'BitBlog.php' );
}

if(!$gBitSystem->isFeatureActive("feature_xmlrpc")) {
  die;
}

// Build map using webservices
$map = array(
	"blogger.newPost" => array("function" => "newPost"),
	"blogger.getUserInfo" => array("function" => "getUserInfo"),
	"blogger.getPost" => array("function" => "getPost"),
	"blogger.editPost" => array("function" => "editPost"),
	"blogger.deletePost" => array("function" => "deletePost"),
	"blogger.getRecentPosts" => array("function" => "getRecentPosts"),
	"blogger.getUserInfo" => array("function" => "getUserInfo"),
	"blogger.getUsersBlogs" => array("function" => "getUserBlogs")
);
$s = new xmlrpc_server($map);
/**
 * @ignore
 */
function check_individual($user, $blogid, $perm_name) {
	global $gBitUser;
	// If the user is admin he can do everything
	if ($gBitUser->user_has_permission($user, 'bit_p_blog_admin'))
		return true;
	// If no individual permissions for the object then ok
	if (!$gBitUser->object_has_one_permission($blogid, 'blog'))
		return true;
	// If the object has individual permissions then check
	// Now get all the permissions that are set for this type of permissions 'image gallery'
	if ($gBitUser->object_has_permission($user, $blog_id, 'blog', $perm_name)) {
		return true;
	} else {
		return false;
	}
}
/**
 * Validates the user and returns user information
 * @ignore
 */
function getUserInfo($params) {
	global $gBitSystem, $gBitUser;
	$appkeyp = $params->getParam(0);
	$appkey = $appkeyp->scalarval();
	$usernamep = $params->getParam(1);
	$username = $usernamep->scalarval();
	$passwordp = $params->getParam(2);
	$password = $passwordp->scalarval();
	if ($gBitUser->validate($username, $password, '', '')) {
		$myStruct = new xmlrpcval(array(
			"nickname" => new xmlrpcval($username),
			"firstname" => new xmlrpcval("none"),
			"lastname" => new xmlrpcval("none"),
			"email" => new xmlrpcval("none"),
			"userid" => new xmlrpcval("$username"),
			"url" => new xmlrpcval("none")
		), "struct");
		return new xmlrpcresp($myStruct);
	} else {
		return new xmlrpcresp(0, 101, "Invalid username or password");
	}
}
/**
 * Posts a new submission to the CMS
 * @ignore
 */
function newPost($params) {
	global $gBitSystem, $gBitUser, $gBlog;
	$appkeyp = $params->getParam(0);
	$appkey = $appkeyp->scalarval();
	$blogidp = $params->getParam(1);
	$blogid = $blogidp->scalarval();
	$usernamep = $params->getParam(2);
	$username = $usernamep->scalarval();
	$passwordp = $params->getParam(3);
	$password = $passwordp->scalarval();
	$passp = $params->getParam(4);
	$content = $passp->scalarval();
	$passp = $params->getParam(5);
	$publish = $passp->scalarval();
	// Now check if the user is valid and if the user can post a submission
	if (!$gBitUser->validate($username, $password, '', '')) {
		return new xmlrpcresp(0, 101, "Invalid username or password");
	}
	// Get individual permissions for this weblog if they exist
	if (!check_individual($username, $blogid, 'bit_p_blog_post')) {
		return new xmlrpcresp(0, 101, "User is not allowed to post to this weblog due to individual restrictions for this weblog");
	}
	// If the blog is not public then check if the user is the owner
	if (!$gBitUser->user_has_permission($username, 'bit_p_blog_admin')) {
		if (!$gBitUser->user_has_permission($username, 'bit_p_blog_post')) {
			return new xmlrpcresp(0, 101, "User is not allowed to post");
		}
		$blog_info = $gBitSystem->get_blog($blogid);
		if ($blog_info["public"] != 'y') {
			if ($username != $blog_info["user"]) {
				return new xmlrpcresp(0, 101, "User is not allowed to post");
			}
		}
	}
	// User ok and can submit then submit the post
//	$now = date("U");
	$id = $gBlog->blog_post($blogid, $content, $username);
	return new xmlrpcresp(new xmlrpcval("$id"));
}

/**
 * @todo editPost
 * @ignore
 */
function editPost($params) {
	global $gBitSystem, $gBitUser, $gBlog;
	$appkeyp = $params->getParam(0);
	$appkey = $appkeyp->scalarval();
	$blogidp = $params->getParam(1);
	$postid = $blogidp->scalarval();
	$usernamep = $params->getParam(2);
	$username = $usernamep->scalarval();
	$passwordp = $params->getParam(3);
	$password = $passwordp->scalarval();
	$passp = $params->getParam(4);
	$content = $passp->scalarval();
	$passp = $params->getParam(5);
	$publish = $passp->scalarval();
	$blogUser = new BitUser($username);

	// Now check if the user is valid and if the user can post a submission
	if (!$gBitUser->validate($username, $password, '', '')) {
		return new xmlrpcresp(0, 101, "Invalid username or password");
	}
	if (!check_individual($username, $blogid, 'bit_p_blog_post')) {
		return new xmlrpcresp(0,
			101, "User is not allowed to post to this weblog due to individual restrictions for this weblog therefor the user cannot edit a post");
	}
	if (!$gBitUser->user_has_permission($username, 'bit_p_blog_post')) {
		return new xmlrpcresp(0, 101, "User is not allowed to post");
	}
	// Now get the post information
	$post_data = $gBlog->get_post($postid);
	if (!$post_data) {
		return new xmlrpcresp(0, 101, "Post not found");
	}
	if ($post_data["user"] != $username) {
		if (!$gBitUser->user_has_permission($username, 'bit_p_blog_admin')) {
			return new xmlrpcresp(0, 101, "Permission denied to edit that post since the post does not belong to the user");
		}
	}
//	$now = date("U");
	$id = $gBlog->update_post($postid, $content, $blogUser->mUserId);
	return new xmlrpcresp(new xmlrpcval(1, "boolean"));
}
/**
 * @todo deletePost
 * @ignore
 */
function deletePost($params) {
	global $gBitSystem, $gBitUser, $gBlog;
	$appkeyp = $params->getParam(0);
	$appkey = $appkeyp->scalarval();
	$blogidp = $params->getParam(1);
	$postid = $blogidp->scalarval();
	$usernamep = $params->getParam(2);
	$username = $usernamep->scalarval();
	$passwordp = $params->getParam(3);
	$password = $passwordp->scalarval();
	$passp = $params->getParam(4);
	$publish = $passp->scalarval();
	// Now check if the user is valid and if the user can post a submission
	if (!$gBitUser->validate($username, $password, '', '')) {
		return new xmlrpcresp(0, 101, "Invalid username or password");
	}
	// Now get the post information
	$post_data = $gBlog->get_post($postid);
	if (!$post_data) {
		return new xmlrpcresp(0, 101, "Post not found");
	}
	if ($post_data["user"] != $username) {
		if (!$gBitUser->user_has_permission($username, 'bit_p_blog_admin')) {
			return new xmlrpcresp(0, 101, "Permission denied to edit that post");
		}
	}
//	$now = date("U");
	$id = $gBlog->remove_post($postid);
	return new xmlrpcresp(new xmlrpcval(1, "boolean"));
}
// :TODO: getTemplate
// :TODO: setTemplate
/**
 * @todo getPost
 * @ignore
 */
function getPost($params) {
	global $gBitSystem, $gBitUser, $gBlog;
	$appkeyp = $params->getParam(0);
	$appkey = $appkeyp->scalarval();
	$blogidp = $params->getParam(1);
	$postid = $blogidp->scalarval();
	$usernamep = $params->getParam(2);
	$username = $usernamep->scalarval();
	$passwordp = $params->getParam(3);
	$password = $passwordp->scalarval();
	// Now check if the user is valid and if the user can post a submission
	if (!$gBitUser->validate($username, $password, '', '')) {
		return new xmlrpcresp(0, 101, "Invalid username or password");
	}
	if (!check_individual($username, $blogid, 'bit_p_blog_post')) {
		return new xmlrpcresp(0, 101, "User is not allowed to post to this weblog due to individual restrictions for this weblog");
	}
	if (!$gBitUser->user_has_permission($username, 'bit_p_blog_post')) {
		return new xmlrpcresp(0, 101, "User is not allowed to post");
	}
	if (!$gBitUser->user_has_permission($username, 'bit_p_read_blog')) {
		return new xmlrpcresp(0, 101, "Permission denied to read this blog");
	}
	// Now get the post information
	$post_data = $gBlog->get_post($postid);
	if (!$post_data) {
		return new xmlrpcresp(0, 101, "Post not found");
	}
	#  $dateCreated=date("Ymd",$post_data["created"])."T".date("h:i:s",$post_data["created"]);
	$dateCreated = $gBitSystem->get_iso8601_datetime($post_data["created"]);
	// added dateTime type for blogger compliant xml tag Joerg Knobloch <joerg@happypenguins.net>
	$myStruct = new xmlrpcval(array(
		"userid" => new xmlrpcval($username),
		"dateCreated" => new xmlrpcval($dateCreated, "dateTime.iso8601"),
		"content" => new xmlrpcval($post_data["data"]),
		"postid" => new xmlrpcval($post_data["post_id"])
	), "struct");
	// User ok and can submit then submit an article
	return new xmlrpcresp($myStruct);
}
/**
 * @todo getRecentPosts
 * @ignore
 */
function getRecentPosts($params) {
	global $gBitSystem, $gBitUser, $gBlog;
	$appkeyp = $params->getParam(0);
	$appkey = $appkeyp->scalarval();
	$blogidp = $params->getParam(1);
	$blogid = $blogidp->scalarval();
	$usernamep = $params->getParam(2);
	$username = $usernamep->scalarval();
	$passwordp = $params->getParam(3);
	$password = $passwordp->scalarval();
	$passp = $params->getParam(4);
	$number = $passp->scalarval();
	// Now check if the user is valid and if the user can post a submission
	if (!$gBitUser->validate($username, $password, '', '')) {
		return new xmlrpcresp(0, 101, "Invalid username or password");
	}
	if (!check_individual($username, $blogid, 'bit_p_blog_post')) {
		return new xmlrpcresp(0,
			101, "User is not allowed to post to this weblog due to individual restrictions for this weblog therefore the user cannot edit a post");
	}
	if (!$gBitUser->user_has_permission($username, 'bit_p_blog_post')) {
		return new xmlrpcresp(0, 101, "User is not allowed to post");
	}
	// Now get the post information
	$posts = $gBlog->list_blog_posts($blogid, 0, $number, 'created_desc', '', '');
	if (count($posts) == 0) {
		return new xmlrpcresp(0, 101, "No posts");
	}
	$arrayval = array();
	foreach ($posts["data"] as $post) {
		#    $dateCreated=date("Ymd",$post["created"])."T".date("h:i:s",$post["created"]);
		$dateCreated = $gBitSystem->get_iso8601_datetime($post["created"]);
		$myStruct = new xmlrpcval(array(
			"userid" => new xmlrpcval($username),
			"dateCreated" => new xmlrpcval($dateCreated, "dateTime.iso8601"),
			"content" => new xmlrpcval($post["data"]),
			"postid" => new xmlrpcval($post["post_id"])
		), "struct");
		$arrayval[] = $myStruct;
	}
	// User ok and can submit then submit an article
	$myVal = new xmlrpcval($arrayval, "array");
	return new xmlrpcresp($myVal);
}
/**
 * Get the topics where the user can post a new
 * @ignore
 */
function getUserBlogs($params) {
	global $gBitSystem, $gBitUser, $gBlog;
	$appkeyp = $params->getParam(0);
	$appkey = $appkeyp->scalarval();
	$usernamep = $params->getParam(1);
	$username = $usernamep->scalarval();
	$passwordp = $params->getParam(2);
	$password = $passwordp->scalarval();
	$arrayVal = array();
	$blogs = $gBitSystem->list_user_blogs($username, true);
	$foo = parse_url($_SERVER["REQUEST_URI"]);
	$foo1 = httpPrefix(). str_replace("xmlrpc", "tiki-view_blog", $foo["path"]);
	foreach ($blogs as $blog) {
		$myStruct = new xmlrpcval(array(
			"blogName" => new xmlrpcval($blog["title"]),
			"url" => new xmlrpcval($foo1 . "?blog_id=" . $blog["blog_id"]),
			"blogid" => new xmlrpcval($blog["blog_id"])
		), "struct");
		$arrayVal[] = $myStruct;
	}
	$myVal = new xmlrpcval($arrayVal, "array");
	return new xmlrpcresp($myVal);
}
?>
