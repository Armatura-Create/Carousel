<?php

namespace Flute\Modules\Carousel\Http\Controllers;

use Flute\Core\Admin\Http\Middlewares\HasPermissionMiddleware;
use Flute\Core\Support\AbstractController;
use Flute\Modules\Carousel\database\Entities\Slide;
use Flute\Modules\Carousel\Services\CarouselService;
use Symfony\Component\HttpFoundation\Response;

class AdminViewController extends AbstractController
{
    public function __construct()
    {
        HasPermissionMiddleware::permission(['admin', 'admin.system']);
        $this->middleware(HasPermissionMiddleware::class);
    }

    public function index() : Response
    {
        $table = table();
        $table
            ->fromEntity(rep(Slide::class)->findAll(), ['image'])
            ->withActions('carousel');

        return view(mm('Carousel', 'Http/Views/admin/list'), [
            'table' => $table->render()
        ]);
    }

    public function update( $id, CarouselService $carouselService ) : Response
    {
        try {
            $slide = $carouselService->find($id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 404);
        }

        return view(mm('Carousel', 'Http/Views/admin/edit'), [
            'slide' => $slide
        ]);
    }

    public function create() : Response
    {
        return view(mm('Carousel', 'Http/Views/admin/add'));
    }
}