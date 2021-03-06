<?php

// $Header$

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

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
		simple_set_toggle( $item, XMLRPC_PKG_NAME );
	}
}

// TODO - not sure how this stuff works - wolff_borg
//$gBitSystem->setHelpInfo('Features','Settings','Help with the features settings');

?>
