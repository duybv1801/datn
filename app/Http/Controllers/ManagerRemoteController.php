<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Traits\HasPermission;
use App\Repositories\RemoteReponsitory;
use App\Http\Requests\CreateRemoteRequest;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Storage;
use App\Mail\ApproveEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManagerRemoteController  extends AppBaseController
{
    use HasPermission;
    private $remoteReponsitory;
    public function __construct(RemoteReponsitory $remoteRepo)
    {
        $this->remoteReponsitory = $remoteRepo;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('read')) {
            return redirect()->back();
        }
        $searchParams = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'query' => $request->input('query'),
        ];
        $manager_remotes = $this->remoteReponsitory->searchByConditions($searchParams)
            ->orderByDesc('created_at')
            ->paginate(10);
        foreach ($manager_remotes as $remote) {
            $remote->from_datetime = Carbon::parse($remote->from_datetime);
            $remote->to_datetime = Carbon::parse($remote->to_datetime);
        }

        return view('remote.manager.index')->with('manager_remotes', $manager_remotes);
    }


    public function edit($id, Request $request)
    {
        if (!$request->user()->hasPermission('update')) {
            return redirect()->back();
        }

        $manager_remotes = $this->remoteReponsitory->find($id);
        $status = $manager_remotes->status;
        if ($status === 2 || $status === 3 || $status === 4) {
            Flash::error(trans('Cannot Approve !'));
            return redirect(route('manager_remote.index'));
        }

        return view('remote.manager.edit')->with('manager_remotes', $manager_remotes);
    }


    public function approve($id, Request $request)
    {
        if (!$request->user()->hasPermission('update')) {
            return redirect()->back();
        }
        $manager_remotes = $this->remoteReponsitory->find($id);
        $user = User::where('id', $manager_remotes->user_id)->first();
        $email = $user->email;
        $status = $request->input('status');
        $reason = $request->input('comment')  ?? '';

        if ($status === '1') {

            Mail::to($email)->send(new ApproveEmail('Approve', $reason));
            $manager_remotes->status = 2;
            $manager_remotes->save();
        } elseif ($status === '3') {
            Mail::to($email)->send(new ApproveEmail('Reject', $reason));
            $manager_remotes->status = 3;
            $manager_remotes->save();
        } else {
            Flash::error(trans('validation.crud.erro_user'));
            return redirect(route('manager_remote.index'));
        }

        Flash::success(trans('validation.crud.approve'));

        return redirect(route('manager_remote.index'));
    }

    public function cancel($id, Request $request)
    {
        if (!$request->user()->hasPermission('update')) {
            return redirect()->back();
        }
        $manager_remotes = $this->remoteReponsitory->find($id);
        $status = $manager_remotes->status;

        if ($status === 2 || $status === 3 || $status === 4) {
            Flash::error(trans('Cannot cancel'));
            return redirect(route('manager_remote.index'));
        }

        $manager_remotes->status = 3;
        $manager_remotes->save();

        Flash::success(trans('validation.crud.cancel'));

        return redirect(route('manager_remote.index'));
    }
}
