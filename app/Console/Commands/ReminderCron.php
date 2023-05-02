<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClientDetail;
use Carbon\Carbon;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;


class ReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    function dispatchEmails($details){
        $receiver = $details['emails'];
        $isArray = $details['isArray'];
        try{
            if($isArray == 1) // array
                Mail::to('noreply@companyname.com')->bcc($receiver)->send(new SendEmail($details));
            else
                Mail::to($receiver)->send(new SendEmail($details));
            return 'email was sent';
        }catch(\Swift_TransportException  $e){
            return 'failed '.$e;
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Reminder Cron is working...");

        $clients = ClientDetail::select('email')->whereRaw('pics IS NULL')->get();
        if(count($clients) > 0){
            foreach($clients as $client){
                $data1 = [
                    'firstname' => $client->fname,
                    'message' => "
                    <div>Your profile was created without a passport, kindly go to ABC Law Firm and submit your passport.</div>
                    ",
                ];
                $details['subj'] = "Your passport photograph is needed";
                $details['data'] = $data1;
                $details['isArray'] = 0; // 1=array, 0=not_array
                $details['email_template'] = "email_templates";
                $details['emails'] = trim($client->email);
                $this->dispatchEmails($details);
            }
        }
    }
}
