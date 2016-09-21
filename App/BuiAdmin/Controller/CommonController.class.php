<?php
/**
 * 后台公共控制器
 * 
 * @author zhuangqianlin 2015-09-01
 */

namespace BuiAdmin\Controller;
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

            $this->assign('top_module_list', $top_module_list);
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
        
        public function getMenu() {
            //获取缓存信息
            //$menu_array = F("menu_list");
            $menu_array = F("menu_list_{$_SESSION['esadmin_user']['role_id']}");

            if(!$menu_array) {
                //获取用户的访问权限
                $user_access = D('Role')->getUserAccessList();

                //{{{ 根据用户访问权限控制菜单显示
                $left_menu_list = array();
                $model_m = D('Module');
                $node_m = D('Node');
                //根据用户访问权限获取顶部菜单
                $module_ids = $node_m->getModuleIdsByIds($user_access);
                $module_pids = $model_m->getPidsByUserModuleIds($module_ids);
                $top_module_list = $model_m->getChildModule(0,$module_pids);

                if($top_module_list) {
                    foreach ($top_module_list as $topkey=>$top_module) {
                        $left_module_list = $model_m->getChildModule($top_module['module_id']);
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

                        $top_module_list[$topkey]['left_menu'] = $left_menu_list;
                    }
                }
                //}}}

                $menu_array = array();
                $i=$j=$k=0;
                if($top_module_list) {
                    foreach ($top_module_list as $topkey => $top_module) {
                        $menu_array[$i][id] = $top_module['module_id'];
                        if($top_module['left_menu']) {
                            foreach ($top_module['left_menu'] as $module) {
                                $menu_array[$i][menu][$j][text] = $module['module']['module_name'];
                                if($module['node']) {
                                    foreach ($module['node'] as $node) {
                                        $menu_array[$i][menu][$j][items][$k][id] = $node['node_url'];
                                        $menu_array[$i][menu][$j][items][$k][text] = $node['node_name'];
                                        $menu_array[$i][menu][$j][items][$k][href] = U($node['node_url']);
                                        $k++;
                                    }

                                }
                                if(!isset($menu_array[$i][menu][$j][items])) {
                                    unset($menu_array[$i][menu][$j]);
                                }
                                $j++;
                            }

                        }
                        $i++;
                    }
                }
                //F('menu_list',$menu_array);
                F("menu_list_{$_SESSION['esadmin_user']['role_id']}",$menu_array);
            }
            
            $this->ajaxReturn($menu_array, 'json');
        }
        
}
?>