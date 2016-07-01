<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Repositories\ReposRepository;
use App\Http\Requests;


class HomeController extends Controller
{

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ReposRepository
     */
    protected $reposRepository;

    public function __construct(CategoryRepository $categoryRepository, ReposRepository $reposRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->reposRepository = $reposRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $repos_count = $this->reposRepository->count();

        return view('admin.dashboard', compact('repos_count'));
    }
}
