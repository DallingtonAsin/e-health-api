<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\email\EmailService;

class SendPendingAppointmentNotifications extends Command
{

    protected $emailService;

  
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending_appointments:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for unsent emails about pending appointments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->emailService->sendPendingAppointmentMail();
    }
}
