<?php

namespace App\Http\Controllers\Front;

use App\Model\Admin\Entity;
use App\Repository\Admin\ContentRepository;

class ContentController extends BaseController
{
    public function show($entityId, $contentId)
    {
        $entity = Entity::query()->External()->findOrFail($entityId);

        ContentRepository::setTable($entity->table_name);
        $content = ContentRepository::findOrFail($contentId);

        return view(
            'front.content.show',
            array_merge(['content' => $content, 'entityId' => $entityId], ContentRepository::adjacent($contentId))
        );
    }
}
