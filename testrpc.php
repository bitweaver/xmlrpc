<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_xmlrpc/testrpc.php,v 1.1.1.1.2.2 2005/09/18 04:23:08 wolff_borg Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: testrpc.php,v 1.1.1.1.2.2 2005/09/18 04:23:08 wolff_borg Exp $
 * @package xmlrpc
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
require_once( UTIL_PKG_PATH.'xmlrpc/xmlrpc.inc' );
require_once( UTIL_PKG_PATH.'xmlrpc/xmlrpcs.inc' );
// EDIT FROM THIS LINE
$server_port = 80;
$server_uri = "localhost";
$server_path = XMLRPC_PKG_URL."xmlrpc.php";
$username = 'admin';
$password = 'admin';
// DON'T EDIT BELOW THIS LINE
$client = new xmlrpc_client("$server_path", "$server_uri", $server_port);
$client->setDebug(1);
$appkey = '';
/*
$blogs=new xmlrpcmsg('blogger.newPost',array(new xmlrpcval($appkey,"string"),
										  new xmlrpcval("1","string"),
										  new xmlrpcval($username,"string"),
										  new xmlrpcval($password,"string"),
										  new xmlrpcval("pepe","string"),
										  new xmlrpcval(0,"boolean"),
										  ));
*/
// Introspection mechanism
$blogs = new xmlrpcmsg('system.listMethods', "");
$result = $client->send($blogs);
if (!$result) {
	$errorMsg = 'Cannot send message to server maybe the server is down';
} else {
	if (!$result->faultCode()) {
		$blogs = php_xmlrpc_decode($result->value());
		print_r ($blogs);
	} else {
		$errorMsg = $result->faultstring();
		print ("Error: $errorMsg<br/>");
	}
}
?>
