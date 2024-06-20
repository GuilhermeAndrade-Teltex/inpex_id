<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\SendToEmail;

class SendEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails from send_to_email table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $emails = SendToEmail::whereIn('status', ['NOT_SEND', 'ERROR'])->get();

        foreach ($emails as $email) {
            $mailerConfig = $email->config_file ?: 'intranet';

            config([
                'mail.mailers' => [
                    'custom_mailer' => config("mail.mailers.$mailerConfig")
                ]
            ]);

            config(['mail.default' => 'custom_mailer']);

            try {
                Mail::send('emails.default', [
                    'page_title' => $email->page_title,
                    'header_description' => $email->header_description,
                    'content_title' => $email->content_title,
                    'content_description' => $email->content_description,
                ], function ($message) use ($email) {
                    $message->to($email->send_to);
                   
                    $message->subject($email->page_title);

                    if (!empty ($email->send_cc)) {
                        $message->cc(explode(',', $email->send_cc));
                    }

                    if (!empty ($email->send_bcc)) {
                        $message->bcc(explode(',', $email->send_bcc));
                    }

                    if (!empty ($email->attach)) {
                        $attachments = explode(',', $email->attach);
                        foreach ($attachments as $attachment) {
                            $message->attach($attachment);
                        }
                    }
                });

                $email->status = 'SEND';
                $email->save();
            } catch (\Exception $e) {
                $email->status = 'ERROR';
                $email->log = $e->getMessage();
                $email->save();
            }
        }

        $this->info('Emails sent successfully.');
    }
}
