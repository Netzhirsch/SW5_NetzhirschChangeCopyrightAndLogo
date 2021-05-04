{extends file="parent:frontend/index/footer.tpl"}
{block name='frontend_index_footer_copyright'}
    {if ($direction == 'horizontal')}
		{assign 'direction' ' netzhirsch--footer-bottom-horizontal'}
    {else }
        {assign 'direction' ' netzhirsch--footer-bottom-vertical'}
    {/if}
	{if ($order == 'disclaimerCopyrightLogo') }
		{assign 'class' ' netzhirsch--footer-bottom-disclaimerCopyrightLogo'}
	{elseif ($order == 'disclaimerLogoCopyright') }
        {assign 'class' ' netzhirsch--footer-bottom-disclaimerLogoCopyright'}
	{elseif ($order == 'copyrightDisclaimerLogo') }
        {assign 'class' ' netzhirsch--footer-bottom-copyrightDisclaimerLogo'}
	{elseif ($order == 'copyrightLogoDisclaimer') }
        {assign 'class' ' netzhirsch--footer-bottom-copyrightLogoDisclaimer'}
	{elseif ($order == 'logoDisclaimerCopyright') }
        {assign 'class' ' netzhirsch--footer-bottom-logoDisclaimerCopyright'}
	{elseif ($order == 'logoCopyrightDisclaimer') }
        {assign 'class' ' netzhirsch--footer-bottom-logoCopyrightDisclaimer'}
	{/if}
	<div class="footer--bottom{$direction}{$class}">

        {* Vat info *}
        {block name='frontend_index_footer_vatinfo'}
	        <div class="footer--vat-info">
				<p class="vat-info--text">
                    {if $sOutputNet}
                        {s name='FooterInfoExcludeVat' namespace="frontend/index/footer"}{/s}
                    {else}
                        {s name='FooterInfoIncludeVat' namespace="frontend/index/footer"}{/s}
                    {/if}
				</p>
			</div>
        {/block}

        {block name='frontend_index_footer_minimal'}
            {include file="frontend/index/footer_minimal.tpl" hideCopyrightNotice=true}
        {/block}

        {* Shopware footer *}
        {block name="frontend_index_shopware_footer"}

            {* Copyright *}
            {block name="frontend_index_shopware_footer_copyright"}
		        <div class="footer--copyright">
	                {if empty($copyright)}
	                        {s name="IndexCopyright"}{/s}
	                {else}
	                    <p>
		                    {$copyright}
	                    </p>
	                {/if}
		        </div>
            {/block}

            {* Logo *}
            {block name="frontend_index_shopware_footer_logo"}
                {if !$hideFooterLogo }
                    {if  is_string($footerLogo) }
				        <div class="netzhirsch--footer--logo">
					        <img src="{$footerLogo}" alt="{$footerLogoAlt}" title="{$footerLogoTitle}">
				        </div>
                    {else}
                        {$smarty.block.parent}
                    {/if}
                {/if}
            {/block}
        {/block}
	</div>
{/block}
