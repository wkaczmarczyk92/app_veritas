<?php

use Illuminate\Support\Facades\Route;

use App\Console\Commands\CRONUpdateUserDays;
use App\Console\Commands\CRONUpdateCaretakerRecruiter;
use App\Console\Commands\CRONCaretakerProfileExists;
use App\Console\Commands\CRONSendSMSWithPassword;
use App\Console\Commands\CRONSendEmailWithOffers;

// CRON-y

Route::middleware('cron_auth')->prefix('cron')->group(function() {

    Route::get('/carer.profile.exists', [CRONCaretakerProfileExists::class, 'handle'])->name('carer.profile.exists');

    Route::get('/update.user.days', [CRONUpdateUserDays::class, 'handle'])->name('update.user.days');

    Route::get('/update.caretaker.recruiter', [CRONUpdateCaretakerRecruiter::class, 'handle'])->name('update.caretaker.recruiter');

    Route::get('/sms.with.password', [CRONSendSMSWithPassword::class, 'handle'])->name('sms.with.password');

    Route::get('/send.emails.with.offers', [CRONSendEmailWithOffers::class, 'handle'])->name('send.emails.with.offers');

});
