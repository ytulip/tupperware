<?php

namespace App\Console\Commands;

use App\Model\DomainExpires;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class Inspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $this->comment(PHP_EOL.Inspiring::quote().PHP_EOL);
//        echo '123';
        $list = DomainExpires::get();
        foreach ($list as $domain)
        {
            //如果
            $API_KEY 	= "dLiMJQdQkUoQ_8y2uBgSMzwRfuDkPwA51WM";
            $API_SECRET = "8y2zcXPkfWx4fnufXubfLZ";

            $url = "https://api.godaddy.com/v1/domains/". $domain->domain_name;
//youtubemyvideos.com
            $header = array(
                'Authorization: sso-key '.$API_KEY.':'.$API_SECRET.''
            );
            $ch = curl_init();
            $timeout=60;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);
            curl_close($ch);
//            echo $result;
            $domainDetail = json_decode($result, true);


//            print_r($domainDetail);
//            var_dump($domainDetail);
            if( isset($domainDetail['expires']) )
            {
                $domain->status = 1;
                $domain->expires = substr($domainDetail['expires'], 0, 10);
            } else {
                $domain->status = 2;
            }
            $domain->result = $result;
            $domain->save();
//            $endDate = date('Y-m-d', strtotime('+7 day'));
//            $domains = json_decode($_REQUEST['domains']);

//            $list = DomainExpires::whereIn('domain_name', $domains)->where('expires', '<', $endDate)->get();
//            return $this->jsonReturn(1, $list);
        }

        echo 123;
    }
}
