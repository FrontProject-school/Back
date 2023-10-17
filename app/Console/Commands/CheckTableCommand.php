<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Program;
use App\Models\Applicant;
use App\Models\Notify;


use Illuminate\Support\Facades\DB;

class CheckTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-table-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {   
        $value1 = Program::where('rEnd', '<', now())
                    // 1분 전 시간과 현재 사이의 레코드만 선택
                    ->where('rEnd', '>', now()->subMinute()) 
                    ->pluck('pId')
                    ->toArray();

        $value2 = Applicant::whereIn('program',$value1)
                    ->where('selected', 'T')
                    ->get(['stuId', 'program']);
        
        foreach ($value2 as $item) {
            Notify::firstOrCreate([         // firstOrCreate 메서드는 동일 값 존재시 pass 해줌
                'stuId' => $item['stuId'],
                'pId' => $item['program'],
            ]);
        }            

        $this->info("알림이 생성 되었습니다.");
    }
}
