{extends file="parent:/frontend/index/footer_minimal.tpl"}
{block name="frontend_index_minimal_footer_copyright"}
	{if empty($copyright)}
		<div class="footer--copyright">
            {s name="IndexCopyright" namespace="frontend/index/footer"}{/s}
		</div>
    {else}
        {$copyright}
    {/if}
    {if !$hideFooterLogo }
        {if  is_string($footerLogo) }
			<div class="netzhirsch--footer--logo" style="display: flex;justify-content: center">
				<img src="{$footerLogo}" alt="{($footerLogoAlt) ? $footerLogoAlt : 'Footer Logo'}">
			</div>
        {else}
            {$smarty.block.parent}
        {/if}
    {/if}
{/block}
