<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveOT extends Mailable
{
    use Queueable, SerializesModels;

    public $input;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function build()
    {
        return $this->view('mail.approve_ot')
            ->subject(trans('overtime.Approval'))
            ->with([
                'status' => $this->input['status'],
                'comment' => $this->input['comment'],
            ]);
    }
}
