<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommentNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $comment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function  __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.comment_task')
            ->subject('Новый комментарий в заявке')
            ->with(['comment' => $this->comment]);
    }
}
