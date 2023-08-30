<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Traits\HasPermission;
use App\Repositories\RemoteReponsitory;
use App\Http\Requests\CreateRemoteRequest;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
        $userId = $request->user()->id;
        $remotes = $this->remoteReponsitory->findByUserId($userId);
        foreach ($remotes as $remote) {
            $remote->from_datetime = Carbon::parse($remote->from_datetime);
            $remote->to_datetime = Carbon::parse($remote->to_datetime);
        }

        return view('remote.registration.index', compact('remotes'));
    }
    public function create(Request $request)
    {
        return view('remote.registration.create');
    }

    public function store(CreateRemoteRequest $request)
    {
        $request->validated();
        $input = $request->all();
        $totalHours = $request->total_hours;
        $avatar = $request->file('evident');
        if ($avatar) {
            $path = 'public/upload/' . date('Y/m/d');
            $filename = Str::random(10) . '.' . $avatar->getClientOriginalExtension();
            $image_path = $avatar->storeAs($path, $filename);
            $image_url = Storage::url($image_path);
            $input['evident'] = $image_url;
        }
        $input['total_hours'] = $totalHours;
        $this->remoteReponsitory->create($input);
        Flash::success(trans('Add New Complete'));

        return redirect(route('remote.index'));
    }

    public function edit($id)
    {
        $remote = $this->remoteReponsitory->find($id);

        $currentTime = now();
        $registrationTime = $remote->from_datetime;
        $status = $remote->status;

        if ($currentTime->greaterThanOrEqualTo($registrationTime) || $status === 2 || $status === 3 || $status === 4) {
            Flash::error(trans('Cannot Edit'));
            return redirect(route('remote.index'));
        }

        return view('remote.registration.edit')->with('remote', $remote);
    }


    public function update($id, CreateRemoteRequest $request)
    {
        $remotes = $this->remoteReponsitory->find($id);
        $input =  $request->all();
        if (empty($remotes)) {
            Flash::error(trans('validation.crud.erro_user'));

            return redirect(route('remote.index'));
        }
        $avatar = $request->file('evident');
        if ($avatar) {
            $path = 'public/upload/' . date('Y/m/d');
            $filename = Str::random(10) . '.' . $avatar->getClientOriginalExtension();
            $image_path = $avatar->storeAs($path, $filename);
            $image_url = Storage::url($image_path);
            $input['evident'] = $image_url;
        }
        if ($remotes->evident) {
            $old_image_path = str_replace('/storage', 'public', $remotes->evident);
            if (Storage::exists($old_image_path)) {
                Storage::delete($old_image_path);
            }
        }

        $this->remoteReponsitory->update($input, $id);
        Flash::success(trans('validation.crud.updated'));

        return redirect(route('remote.index'));
    }

    public function cancel($id)
    {
        $remote = $this->remoteReponsitory->find($id);

        $currentTime = now();
        $registrationTime = $remote->from_datetime;
        $status = $remote->status;

        if ($currentTime->greaterThanOrEqualTo($registrationTime) || $status === 2 || $status === 3 || $status === 4) {
            Flash::error(trans('Cannot cancel'));
            return redirect(route('remote.index'));
        }

        $remote->status = 4;
        $remote->save();

        Flash::success(trans('validation.crud.cancel'));

        return redirect(route('remote.index'));
    }
}
