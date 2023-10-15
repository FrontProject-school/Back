<?php

namespace App\Http\Logics;

use App\Models\Image;

class ImageLogic
{
    // 이미지 등록
    public function insertImgs($imgList, $uId, $category){
        foreach ($imgList as $item) {
            $image = new Image;

            $imgName = $item->getClientOriginalName();
            $item->storeAs($category, $imgName, $category);

            $image->category = $category;
            $image->uId = $uId;
            $image->imgName = $imgName;

            $image->save();
        }
    }

    // 이미지 불러오기
    public function showImgs($uId, $category){
        $images = Image::where([
            ['category', '=', $category],
            ['uId', '=', $uId]
        ])->get();

        return $images;
    }

    // 이미지 삭제
    public function deleteImgs($uId, $category){
        Image::where([
            ['category', '=', $category],
            ['uId', '=', $uId]
        ])->delete();
    }

}
