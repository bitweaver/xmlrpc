{* $Header: /cvsroot/bitweaver/_bit_xmlrpc/templates/admin_xmlrpc.tpl,v 1.2 2006/03/01 20:16:37 spiderr Exp $ *}
{strip}
{form legend="XML RPC Features"}
	<input type="hidden" name="page" value="{$page}" />

	{foreach from=$formFeaturesXmlrpc key=feature item=output}
		<div class="row">
			{formlabel label=`$output.label` for=$feature}
			{forminput}
				{html_checkboxes name="$feature" values="y" checked=`$gBitSystem->getConfig('')$feature` labels=false id=$feature}
				{formhelp note=`$output.note` page=`$output.page`}
			{/forminput}
		</div>
	{/foreach}

	<div class="row submit">
		<input type="submit" name="adminTabSubmit" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
{/strip}
