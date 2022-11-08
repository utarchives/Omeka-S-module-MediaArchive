<?php
namespace MediaArchive\View\Helper;

use Omeka\Api\Representation\AbstractResourceEntityRepresentation;
use Zend\View\Helper\AbstractHelper;

class MediaArchive extends AbstractHelper {

    public function __invoke(AbstractResourceEntityRepresentation $resource)
    {
        $view = $this->getView();
        $view->headScript()->appendFile($view->assetUrl('js/media-archive.js', 'MediaArchive'));
        $download = false;
        if(count($resource->media()) > 0) {
            $download = true;
        }
        return $view->partial('common/media-archive',
            [
                'id' =>  $resource->id(),
                'download' => $download,

            ]);
    }

}