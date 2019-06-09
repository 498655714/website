<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\Controller;
use App\Models\WebsiteSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebsiteSetupController extends Controller
{
    /**
     * 网站设置
     * WebsiteSetupController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        if(Cache::has('admin_website_setup')){
            $setups = Cache::get('admin_website_setup');
        }else{
            $setups = array_column(WebsiteSetup::all()->toArray(),'value','name');
            Cache::forever('admin_website_setup',$setups);
        }
        return view('admin.websiteSetups.index',compact('setups'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request){
        $this->validate($request,[
            'site_name'=>'required|max:255',
            'domain'=>'required|max:255',
            'cache'=>'required|max:255',
            'max_upload'=>'required|max:255',
            'ext_upload'=>'required|max:255',
            'keywords'=>'required|max:255',
            'description'=>'required|max:255',
            'copyright'=>'required|max:255',
        ]);
        $sites = $request->except(['_token']);
        foreach($sites as $key=>$site){
            WebsiteSetup::where(['name'=>$key])->update(['value'=>$site]);
        }
        Cache::forget('admin_website_setup');
        $setups = array_column(WebsiteSetup::all()->toArray(),'value','name');
        Cache::forever('admin_website_setup',$setups);

        return $this->success('保存设置成功');
    }
}
