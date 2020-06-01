<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\PassCodeMail;
use App\Mail\PasswordEmail;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 60;
    protected $details; 
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($this->details['pass_code'])) {
            $pm = new PassCodeMail(array("passcode" => $this->details['pass_code']));
        } elseif (!empty($this->details['password'])) {
            $pm = new PasswordEmail(array("password" => $this->details['password']));
        } elseif (!empty($this->details['token'])) {
            $pm = new ForgotPasswordEmail(array("token" => $this->details['token']));
        }
        
        Mail::to($this->details['email'])->send($pm);
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed()
    {
        if ($this->job) {
            // This needs an Exception as argument according to it's interface.
            return $this->job->failed(); 
        }
    }
}
