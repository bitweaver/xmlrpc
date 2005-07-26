<?php

// $Header: /cvsroot/bitweaver/_bit_xmlrpc/admin/admin_xmlrpc_inc.php,v 1.1.1.1.2.1 2005/07/26 15:50:49 drewslater Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

$formFeaturesXmlrpc = array(
	'feature_comm' => array(
		'label' => 'Communications<br />(send/receive objects)',
		'note' => 'This allows you to send and recieve pages from other bitweaver/TikiWiki sites',
		'page' => 'Communications',
	),
	'feature_xmlrpc' => array(
		'label' => 'XMLRPC API',
		'note' => 'This API is used by several Windows applications that can be used to manage your weblogs, any application implementing the Blogger XMLRPC API can be used to edit Tiki blogs.<br />Note that this is also required to send and recieve objects.',
		'page' => 'XmlrpcApi',
	),
);

$gBitSmarty->assign( 'formFeaturesXmlrpc',$formFeaturesXmlrpc );

$processForm = set_tab();

if( $processForm ) {
	
	foreach( $formFeaturesXmlrpc as $item => $data ) {
		simple_set_toggle( $item );
	}
}

// TODO - not sure how this stuff works - wolff_borg
//$gBitSystem->setHelpInfo('Features','Settings','Help with the features settings');


?>
