<?php
namespace NetzhirschChangeCopyrightAndLogo;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;

class NetzhirschChangeCopyrightAndLogo extends Plugin
{
	public function install(InstallContext $context)
	{
		$cacheManager = Shopware()->Container()->get('shopware.cache_manager');
		$cacheManager->clearHttpCache();
		$cacheManager->clearTemplateCache();
		$context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
	}
	
	public function activate(ActivateContext $context)
	{
		$cacheManager = Shopware()->Container()->get('shopware.cache_manager');
		$cacheManager->clearHttpCache();
		$cacheManager->clearTemplateCache();
		$context->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
	}
}