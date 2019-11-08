<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Common\Controller as BaseController;
class UploadsController extends BaseController
{

    /**
     *   @desc  单图片上传类
     *   @date  2019/3/18 11:19
     *   @parm $maxSize //上传文件最大大小,单位M
     *   @parm $allowed_extensions //支持的上传图片类型
     **/

    public function uploadImg(Request $request){
        //上传文件最大大小,单位KB
        //$maxSize = env('MAX_SIZE');
        $maxSize = 200;//不建议设置值过大,虽然做了压缩处理,过大网站依然加载很慢。
        //支持的上传图片类型
        $allowed_extensions = ["png", "jpg", "gif","jpeg"];

        $file = $request->file('file');

        //检查文件是否上传完成
        if ($file->isValid()){
            //检测图片类型
            $ext = $file->getClientOriginalExtension();
            if (!in_array(strtolower($ext),$allowed_extensions)){
                $msg = "请上传".implode(",",$allowed_extensions)."格式的图片";
                return $this->failed($msg,200);
            }
            //检测图片大小
            if ($file->getClientSize() > $maxSize*1024){
                $msg = "图片大小限制".$maxSize."KB";
                return $this->failed($msg,200);
            }
        }else{
            $msg = $file->getErrorMessage();
            return $this->failed($msg,200);
        }
        $newFile = date('Y-m-d')."_".time()."_".uniqid().".".$file->getClientOriginalExtension();
        //图片压缩处理
        if($file->getClientSize() > 50*1024 && ($file->getClientMimeType() == "image/png" || $file->getClientMimeType() == "image/jpeg")){

            list($width,$height) = getimagesize($file->getRealPath());//获取图片长宽赋值
            if($file->getClientMimeType() == "image/png"){
                $src = @imagecreatefrompng($file->getRealPath());//获取图片文件流
                $tmp = imagecreatetruecolor($width,$height);
                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);//不改变宽高、速度慢质量高

                imagepng($tmp, $file->getRealPath(),8);//压缩后的文件，写入缓存文件内
                imagedestroy($tmp);//清除生成文件
            }
            if($file->getClientMimeType() == "image/jpeg"){
                $src = @imagecreatefromjpeg($file->getRealPath());//获取图片文件流
                $tmp = imagecreatetruecolor($width,$height);
                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);//不改变宽高、速度慢质量高
                imagejpeg($tmp, $file->getRealPath(),85);//压缩后的文件，写入缓存文件内
                imagedestroy($tmp);//清除生成文件
            }
        }
        $res = Storage::disk('uploads')->put($newFile,file_get_contents($file->getRealPath()));

        if($res){
            $fiepach = 'uploads/'.date('Ymd').'/'.$newFile;
        }else{
            $msg = $file->getErrorMessage();
            return $this->failed($msg,200);
        }
        $data=['flag'=>'success','message'=>'上传成功','fiepach'=>$fiepach];
        return $this->success($data);
    }
}
