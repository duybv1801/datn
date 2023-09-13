<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveOT extends Mailable
{
    use Queueable, SerializesModels;

    public $overtime;

    public function __construct($overtime)
    {
        $this->overtime = $overtime;

        $statusLabels = [
            config('define.overtime.admin_approve') => trans('overtime.admin_approve'),
            config('define.overtime.registered') => trans('overtime.registered'),
            config('define.overtime.approved') => trans('overtime.approved'),
            config('define.overtime.confirm') => trans('overtime.confirm'),
            config('define.overtime.admin_confirm') => trans('overtime.admin_confirm'),
            config('define.overtime.confirmed') => trans('overtime.confirmed'),
            config('define.overtime.rejected') => trans('overtime.rejected'),
            config('define.overtime.cancel') => trans('overtime.cancel'),
        ];
        $overtime->status_label = $statusLabels[$overtime->status] ?? '';
        $this->overtime = $overtime;
    }


    public function build()
    {
        return $this->view('mail.approve_ot')
            ->subject(trans('overtime.Approval'))
            ->with([
                'overtime' => $this->overtime,
            ]);
    }
}
