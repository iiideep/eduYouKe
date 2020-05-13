<?php
namespace app\admin\controller;
use app\AdminBaseController;
use app\logic\AdminRole as Role;
use think\facade\View;


class Rbac extends AdminBaseController
{

    /**
     * [AdminUserList 管理员列表]
     */
    public function RbacList()
    {

        $role = new Role();
        View::assign('rbaclist', $role->where('delete_status', 0)->select());
        return View::fetch();
    }


     /**
     * [add 添加管理员]
     */
    public function add()
    {
        return View::fetch();
    }

    public function addPost()
    {

        $param = $this->request->param();
        
        $User  = new User();
        $data  = [
            'nickname' => $param['nickname'],
            'password' => Tools::userMd5($param['password']),
            'mobile' => $param['mobile'],
            'role_id' => $param['role_id'],
            'avatar_url' => !empty($param['avatar_url']) ? $param['avatar_url'] : '',
            'show_status' => !empty($param['show_status']) ? 1 : 0,
        ];
        $User->save($data);

    }

    /**
     * [edit 编辑展示页面]
     * @return [type] [description]
     */
    public function edit()
    {
        $param = $this->request->param();
        $User  = new User();
        View::assign('editData', $User->getAdminUserInfo($param['id']));
        return View::fetch();
    }

    /**
     * [editPost 编辑提交]
     * @return [type] [description]
     */
    public function editPost()
    {
        $param = $this->request->param();
        $param['password'] = Tools::userMd5($param['password']);
        $param['show_status'] = !empty($param['show_status']) ? 1 : 0;
        
        $User  = new User();
        $userData = $User->find($param['id']);
        $result = $userData->allowField([
            'nickname',
            'password',
            'mobile',
            'role_id',
            'avatar_url',
            'show_status',
        ])->save($param);

    }

    /**
     * [delete 删除操作]
     * @return [type] [description]
     */
    public function delete()
    {
        $param = $this->request->param();
        $User  = new User();
        $result = $User->update(['delete_status'=> 1],['id'=>$param['id']]);
        if ($result) {
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }
    



}