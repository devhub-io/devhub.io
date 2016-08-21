<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Entities\Image;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ReposUpdateRequest;
use App\Repositories\ReposRepository;
use App\Validators\ReposValidator;
use Response;
use SEOMeta;


class ReposController extends Controller
{

    /**
     * @var ReposRepository
     */
    protected $repository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ReposValidator
     */
    protected $validator;

    public function __construct(ReposRepository $repository, ReposValidator $validator, CategoryRepository $categoryRepository)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keyword = request()->get('keyword');
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $repos = $this->repository->searchList($keyword);

        return view('admin.repos.index', compact('repos', 'keyword'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $repository = $this->repository->find($id);
        $categories = $this->categoryRepository->all();
        $images = Image::latest('id')->paginate(10);

        SEOMeta::setTitle($repository->title);

        return view('admin.repos.edit', compact('repository', 'categories', 'images'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ReposUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(ReposUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $this->repository->update($request->all(), $id);

            return redirect('admin/repos');
        } catch (ValidatorException $e) {
            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Change enable
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function change_enable($id)
    {
        $repository = $this->repository->find($id);
        $repository->status = !$repository->status == true;
        $repository->save();

        return redirect()->back();
    }

    /**
     * Change recommend
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change_recommend($id)
    {
        $repository = $this->repository->find($id);
        $repository->is_recommend = !$repository->is_recommend == true;
        $repository->save();

        return redirect()->back();
    }

    /**
     * Revision history
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history($id)
    {
        $repository = $this->repository->find($id);
        $history = $repository->revisionHistory;

        SEOMeta::setTitle($repository->title .' - Revision History');

        return view('admin.repos.history', compact('history', 'repository'));
    }
}
