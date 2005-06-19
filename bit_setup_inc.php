<?php
global $gBitSystem;
$gBitSystem->registerPackage('xmlrpc', dirname(__FILE__).'/' );

	if($gBitSystem->isPackageActive( 'xmlrpc' ) ) {
//		$gBitSystem->registerAppMenu( 'xmlrpc', 'XMLRPC', XMLRPC_PKG_URL.'index.php', 'bitpackage:xmlrpc/menu_xmlrpc.tpl', 'xmlrpc');
	}

?>
