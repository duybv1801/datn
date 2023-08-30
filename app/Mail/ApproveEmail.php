<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $status;
    public $comment;

    public function __construct($status, $comment = null)
    {
        $this->status = $status;
        $this->comment = $comment;
    }

    public function build()
    {
        $subject = '';
        $status = ucfirst($this->status);


        if ($status === 'Approve') {
            $subject = 'Bạn đã được đồng ý';
        } elseif ($status === 'Reject') {
            $subject = 'Bạn đã bị từ chối';
        }

        return $this->view('mail.approve')
            ->with(['subject' => $subject, 'comment' => $this->comment]);
    }
}
