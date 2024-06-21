<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyCustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $page_title;
    public $header_description;
    public $content_title;
    public $content_description;

    /**
     * Create a new message instance.
     */
    public function __construct($page_title, $header_description, $content_title, $content_description)
    {
        $this->page_title = $page_title;
        $this->header_description = $header_description;
        $this->content_title = $content_title;
        $this->content_description = $content_description;
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject($this->page_title)
            ->markdown('emails.default')
            ->with([
                'page_title' => $this->page_title,
                'header_description' => $this->header_description,
                'content_title' => $this->content_title,
                'content_description' => $this->content_description,
            ]);
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
