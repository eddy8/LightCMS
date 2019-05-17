<?php

namespace App\Http\Controllers\Front;

use App\Model\Admin\Entity;
use App\Repository\Admin\ContentRepository;

class HomeController extends BaseController
{
    public function index()
    {
        $entities = Entity::query()->External()->get();
        return view('welcome', compact('entities'));
    }

    public function content($entityId)
    {
        $entity = Entity::query()->External()->findOrFail($entityId);

        ContentRepository::setTable($entity->table_name);
        $contents = ContentRepository::paginate();

        return view('front.content.list', compact('entity', 'contents'));
    }
}
