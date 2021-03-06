<?php

$tables = array(
);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( XMLRPC_PKG_NAME, $tableName, $tables[$tableName], TRUE );
}

$indices = array (
);

$gBitInstaller->registerSchemaIndexes( XMLRPC_PKG_NAME, $indices );

$gBitInstaller->registerPackageInfo( XMLRPC_PKG_NAME, array(
	'description' => "This is the XML RPC communications library.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( XMLRPC_PKG_NAME, array(
) );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( XMLRPC_PKG_NAME, array(
	array('p_xmlrpc_send_content', 'Can send pages to other sites', 'registered', XMLRPC_PKG_NAME),
	array('p_xmlrpc_sendme_content', 'Can send pages to this site', 'registered', XMLRPC_PKG_NAME),
	array('p_xmlrpc_admin_content', 'Can admin received pages', 'editors', XMLRPC_PKG_NAME),
) );

?>
