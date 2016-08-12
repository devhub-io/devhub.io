<?php

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
        $repository->status = $repository->status == 1 ? 0 : 1;
        $repository->save();

        return redirect()->back();
    }

}
