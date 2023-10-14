<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    // public $timestamps = false;
    // Eloquent 형식에서는 timestamps가 필수로 들어가는데, 그것을 막아준다

    // 테이블명 지정
    // 지정하지 않으면 Eloquent형식에 의해서 snake형식의 테이블명으로 찾는다
    // 테이블을 만들 때, snake형식이 아니라면 오류가 남
    // 그러니 테이블명을 지정해 주자
    // protected $table = 'programRegisters';

    // 기본키 지정
    protected $primaryKey = 'pId';
    public $incrementing = false; // 기본키로 설정하면 자동 auto_increment가 적용되기 때문에 취소해 주자
    protected $keyType = 'string';

    protected $fillable = [
        'pId',
        'category',
        'title',
        'grade',
        'selectNum',
        'rStart',
        'rEnd',
        'actStart',
        'actEnd'
    ];

    // 파라미터 키값 지정
    public function getRouteKeyName()
    {
        return 'pId';
    }

}
