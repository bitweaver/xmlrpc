<?php
global $gBitSystem;

$registerHash = array(
	'package_name' => 'xmlrpc',
	'package_path' => dirname( __FILE__ ).'/',
);
$gBitSystem->registerPackage( $registerHash );

if($gBitSystem->isPackageActive( 'xmlrpc' ) ) {
//	$gBitSystem->registerAppMenu( XMLRPC_PKG_DIR, 'XMLRPC', XMLRPC_PKG_URL.'index.php', 'bitpackage:xmlrpc/menu_xmlrpc.tpl', 'xmlrpc');
	function check_individual($user, $blogid, $perm_name) {
		// elimnate for now and migrate to liberty_content_perms
/*
		global $gBitUser;
		// If the user is admin he can do everything
		if ($gBitUser->user_has_permission($user, 'p_blogs_admin'))
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
*/
		return true;
	}
}

?>
