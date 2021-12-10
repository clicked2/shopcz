<?php
namespace app\index\controller;
use app\index\model\Nav as NavModel;
use think\Controller;
use app\index\model\Conf as ConfModel;
use app\index\model\Brand as BrandModel;
use app\admin\model\Conf;

class Index extends Controller
{
    public function initialize()
    {
        $nav = new NavModel;
        $conf = new ConfModel;
        $this->config = $conf->_getConf();
        self::assign('conf', $this->config);
        self::assign('nav', $nav->_getNav());
       
    }

    public function index()
    {
        self::assign('brands', self::brands(true));
        return view('index');
    }
   

    public function brands($mid=false)
    {
        $arr = self::sort();
        if ( count($arr) >17 ) {
            $newarr = [];
            for ($i=0; $i < count($arr); $i++) { 
                if ( $i <= 17 ) {
                    $newarr = $arr[$i];
                } else {
                    break;
                }
            }
            $arr = $newarr;
        }
        return $mid ? $arr : json($arr);
    }

    static function sort()
    {
        $arr = BrandModel::select();
        for ($i=0; $i < count($arr); $i++) { 
            self::swap($arr, rand(0, count($arr)-1), rand(0, count($arr)-1));
        }
        return $arr;
    }

    static function swap($arr, $i, $j)
    {
        $temp = $arr[$i];
        $arr[$i] = $arr[$j];
        $arr[$j] = $temp;
    }
}
