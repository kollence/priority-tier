<?php

namespace App\Listeners;

use App\Events\ImportError;
use App\Mail\ImportErrorMail;
// use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

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
        $importLogs = $event->logs->toArray();
        Mail::to(auth()->user()->email)->send(new ImportErrorMail($importLogs));
    }
}
