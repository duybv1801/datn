<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Traits\HasPermission;
use App\Repositories\RemoteReponsitory;
use Illuminate\Support\Facades\Hash;
use Laracasts\Flash\Flash;

class RemoteController  extends AppBaseController
{
    use HasPermission;
    private $remoteReponsitory;
    public function __construct(RemoteReponsitory $remoteRepo)
    {
        $this->remoteReponsitory = $remoteRepo;
    }
    public function index(Request $request)
    {
        $remotes = $this->remoteReponsitory->all();
        $remotes = $this->remoteReponsitory->paginate(10);

        return view('registration_form.registration.index')->with('remotes', $remotes);
    }

    public function create(Request $request)
    {
        if (!$request->user()->hasPermission('create')) {
            return redirect()->back();
        }

        return view('registration_form.registration.create');
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $this->remoteReponsitory->create($input);
        Flash::success(trans('Add New Complete'));

        return redirect(route('registration.index'));
    }
    public function edit($id, Request $request)
    {

        $user = $this->remoteReponsitory->find($id);

        if (empty($user)) {
            Flash::error(trans('validation.crud.show_error'));

            return redirect(route('registration.index'));
        }

        return view('registration.edit')->with('user', $user);
    }


    public function update($id, Request $request)
    {
        $remotes = $this->remoteReponsitory->find($id);

        if (empty($remotes)) {
            Flash::error(trans('validation.crud.erro_user'));

            return redirect(route('registration.index'));
        }
        $input =  $request->all();
        $this->remoteReponsitory->update($input, $id);
        Flash::success(trans('validation.crud.updated'));

        return redirect(route('registration.index'));
    }

    public function destroy($id, Request $request)
    {
        $remotes = $this->remoteReponsitory->find($id);

        if (empty($remotes)) {
            Flash::error(trans('Erros'));

            return redirect(route('registration.index'));
        }

        $this->remoteReponsitory->delete($id);

        Flash::success(trans('validation.crud.delete'));

        return redirect(route('registration.index'));
    }
}
