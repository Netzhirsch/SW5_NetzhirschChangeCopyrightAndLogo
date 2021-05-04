<?php

namespace NetzhirschChangeCopyrightAndLogo\Subscriber;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use Enlight_View;
use Shopware\Bundle\MediaBundle\MediaService;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Components\Theme\LessDefinition;
use Shopware\Models\Media\Repository;

class FrontendSubscriber implements SubscriberInterface
{
	
	const FIRST_THUMBNAIL_SIZE = 'thumbnail_0';
	const SECOND_THUMBNAIL_SIZE = 'thumbnail_1';
	const THIRD_THUMBNAIL_SIZE = 'thumbnail_2';
	const ORIGINAL_THUMBNAIL_SIZE = 'original';
	
	/**
	 * @var Connection
	 */
	private $connection;
	
	private $pluginName;
	
	private $pluginDirectory;
	/**
	 * @var ConfigReader
	 */
	private $config;
	
	/**
	 * @var MediaService
	 */
	private $mediaService;
	
	/**
	 * @param ConfigReader $config
	 * @param $pluginDirectory
	 * @param $pluginName
	 * @param MediaService $mediaService
	 * @param Connection $connection
	 */
	public function __construct(ConfigReader $config, $pluginDirectory,$pluginName, MediaService $mediaService,  Connection $connection)
	{
		$this->config = $config;
		$this->pluginDirectory = $pluginDirectory;
		$this->pluginName = $pluginName;
		$this->mediaService = $mediaService;
		$this->connection = $connection;
	}
	
	public static function getSubscribedEvents()
    {
        return [
	        'Theme_Compiler_Collect_Plugin_Less' => 'addLessFiles',
	        'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'postDispatchFrontend',
        ];
    }
	
	public function addLessFiles() {
		$less = new LessDefinition(array(),
			array(
				$this->pluginDirectory . '/Resources/Views/frontend/_public/src/less/all.less'
			), __DIR__);
		
		return new ArrayCollection(array(
			$less
		));
	}
    public function postDispatchFrontend(Enlight_Event_EventArgs $args)
    {
	    /** @noinspection PhpUndefinedMethodInspection */
	    $subject = $args->getSubject();
	
	    /** @noinspection PhpUndefinedMethodInspection */
	    $view = $subject->View();
	
	    $pluginInfos = $this->config->getByPluginName($this->pluginName);
	    
	    /**
	     * Get footerLogo src
	     */
	    if (!empty($pluginInfos['footerLogo'])) {
		    $path = $this->mediaService->normalize($pluginInfos['footerLogo']);
		
		    $query = $this->connection->createQueryBuilder();
		    $query->select('s_media.id')
			    ->from('s_media', 's_media')
			    ->where('s_media.path = :path')
			    ->setParameter('path', $path);
		    $mediaId =  $query->execute()->fetch()['id'];
		
		    /** @var Repository $repository */
		    $repository = Shopware()->Container()->get('models')->getRepository('Shopware\Models\Media\Media');
		    $mediaObject = $repository->find($mediaId);
		    $thumbs = $mediaObject->getThumbnails();
		
		    uksort($thumbs, function($a, $b){
			    $a = str_replace('/', '', $a);
			    $b = str_replace('/', '', $b);
			    return $a - $b;
		    });
		    
		    switch ($pluginInfos['footerLogoSize']){
			    case self::FIRST_THUMBNAIL_SIZE:
				    $pathThumbnail = $this->mediaService->getUrl(current($thumbs));
				    break;
			    case self::SECOND_THUMBNAIL_SIZE:
				    $pathThumbnail = $this->mediaService->getUrl(next($thumbs));
				    break;
			    case self::THIRD_THUMBNAIL_SIZE:
				    $pathThumbnail = $this->mediaService->getUrl(next(next($thumbs)));
				    break;
			    case self::ORIGINAL_THUMBNAIL_SIZE:
				    $pathThumbnail = $pluginInfos['footerLogo'];
				    break;
			    default:
				    $pathThumbnail = $this->mediaService->getUrl(current($thumbs));
		    }
		
		    $pluginInfos['footerLogo'] = $pathThumbnail;
	    }
	
	    $pluginInfos['copyright'] = str_replace('<space>', '', $pluginInfos['copyright']);
	    
	    $date = new DateTime('now');
	    $pluginInfos['copyright'] = str_replace('{year}', $date->format('Y'), $pluginInfos['copyright']);
	
	
	    foreach ($pluginInfos as $key => $value) {
		    $view->assign($key,$value);
	    }
	    
	    $view->addTemplateDir($this->pluginDirectory . '/Resources/Views/');
    }
}