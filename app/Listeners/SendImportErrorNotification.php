<?php

namespace App\Listeners;

use App\Events\ImportError;
use App\Mail\ImportErrorMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendImportErrorNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ImportError $event) { 
        $importLog = $event->importLog; 
        Mail::send('emails.import_error', ['importLog' => $importLog], function ($message) { 
            $message->to('admin@mail.com') ->subject('New Import Error'); 
        });
    }
}
