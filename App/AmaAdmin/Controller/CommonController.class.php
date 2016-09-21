<?php
/**
 * 后台公共控制器
 * 
 * @author zhuangqianlin 2015-09-01
 */

namespace AmaAdmin\Controller;
use Think\Controller;

class CommonController extends Controller {
	public $loginMarked;
        
	public function _initialize(){
            header('Content-Type:text/html,charset=utf8');
            $sysConfig['TOKEN'] = C('TOKEN');
            ini_set('session_maxlifetime', $sysConfig['TOKEN']['admin_timeout']);
            $this->loginMarked = md5($sysConfig['TOKEN']['admin_marked']);
            
            //判断是否登录
            $this->checkLogin();

            //获取用户的访问权限
            $user_access = D('Role')->getUserAccessList();
            
            //判断访问权限
            $this->checkAccess($user_access);
            
            //{{{ 根据用户访问权限控制菜单显示
            $left_menu_list = array();
            $model_m = D('Module');
            $node_m = D('Node');
            //根据用户访问权限获取顶部菜单
            $module_ids = $node_m->getModuleIdsByIds($user_access);
            $module_pids = $model_m->getPidsByUserModuleIds($module_ids);
            $top_module_list = $model_m->getChildModule(0,$module_pids);
            $left_module_list = $model_m->getChildModule($top_module_list[0]['module_id']);
            if($left_module_list) {
                $module_id_str = '';
                foreach($left_module_list as $module) {
                    $module_id_str .= $module['module_id'].',';
                }
                
                $left_menu_list = array();
                $menu_list = $node_m->getNodesByModuleIds($module_id_str);
                if($menu_list) {
                    foreach($left_module_list as $module) {
                        $left_menu_list[$module['module_id']]['module'] = $module;
                        foreach ($menu_list as $menu){
                            if($module['module_id'] == $menu['module_id'] && in_array($menu['node_id'], explode(',', $user_access))) {
                                $left_menu_list[$module['module_id']]['node'][] = $menu;
                            }
                        }
                    }
                }
            }
            //}}}
            
            //获取当前访问的节点信息
            $c_a = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
            $current_node_info = M('Node')->where("node_url='{$c_a}'")->find();

            $this->assign('current_node_info',$current_node_info);
            $this->assign('top_module_list', $top_module_list);
            $this->assign('left_menu_list', $left_menu_list);
	}
        
        private function checkLogin() {
            if (isset($_COOKIE[$this->loginMarked])) {
                $cookie = explode("_", $_COOKIE[$this->loginMarked]);
                $timeout = C("TOKEN");
                if (time() > (end($cookie) + $timeout['admin_timeout'])) {
                    setcookie("$this->loginMarked", NULL, -3600, "/");
                    unset($_SESSION[$this->loginMarked], $_COOKIE[$this->loginMarked]);
                    $this->error("登录超时，请重新登录", U("Public/login"));
                } else {
                    if ($cookie[0] == $_SESSION[$this->loginMarked]) {
                        setcookie("$this->loginMarked", $cookie[0] . "_" . time(), 0, "/");
                    } else {
                        setcookie("$this->loginMarked", NULL, -3600, "/");
                        unset($_SESSION[$this->loginMarked], $_COOKIE[$this->loginMarked]);
                        $this->error("帐号异常，请重新登录", U("Public/login"));
                    }
                }
            } else {
                $this->redirect("Public/login");
            }
            return TRUE;
        }
        
        /**
         * 检查访问权限
         */
        private function checkAccess($user_access){
            $user_access = explode(',', $user_access);
            if(!in_array(CONTROLLER_NAME, explode(',', C('NOT_AUTH_CONTROLLER')))) {
                $node_url = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
                $res = M('Node')->where("node_url='{$node_url}'")->find();
                if($res) {
                    if(!in_array($res['node_id'], $user_access)) {
                        $this->error("你无权限访问！");
                    }
                }else{
                    $this->error("访问异常，请重新登录", U("Public/login"));
                }
            }
            
            return true;
        }
}
?>