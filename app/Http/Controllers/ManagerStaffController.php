<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStaffRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Hash;

class ManagerStaffController extends AppBaseController
{
    /** @var $userRepository UserRepository */
    private $userRepository;
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->all();
        $users = $this->userRepository->paginate(10);

        return view('manager_staff.index')->with('users', $users); 
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(trans('validation.crud.show_error'));

            return redirect(route('manager_staff.index'));
        }

        return view('manager_staff.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateStaffRequest $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(trans('validation.crud.erro_user'));

            return redirect(route('manager_staff.index'));
        }
        $input =  $request->all();
        $user = $this->userRepository->update($input, $id);
        Flash::success(trans('validation.crud.updated'));
 
        return redirect(route('manager_staff.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('Staff not found');

            return redirect(route('manager_staff.index'));
        }

        $this->userRepository->delete($id);

        Flash::success(trans('validation.crud.delete'));

        return redirect(route('manager_staff.index'));
    }
}
