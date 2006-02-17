<?php
global $gBitSystem;

$registerHash = array(
	'package_name' => 'xmlrpc',
	'package_path' => dirname( __FILE__ ).'/',
);
$gBitSystem->registerPackage( $registerHash );

//if($gBitSystem->isPackageActive( 'xmlrpc' ) ) {
//	$gBitSystem->registerAppMenu( XMLRPC_PKG_DIR, 'XMLRPC', XMLRPC_PKG_URL.'index.php', 'bitpackage:xmlrpc/menu_xmlrpc.tpl', 'xmlrpc');
//}

?>
