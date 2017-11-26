<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
    /**
     * 中间件过滤权限
     */
    public function __construct()
    {
      $this->middleware('auth',[
        'except' => ['show','create','store','index']
      ]);

      $this->middleware('guest', [
            'only' => ['create']
      ]);
    }

    /**
     * 列出所有用户
     */
     public function index()
     {
       $users = User::paginate(10);
       return view('users.index',compact('users'));
     }

    /**
     * 显示注册页面
     */
    public function create()
    {
      return view('users.create');
    }

    /**
     * 显示用户个人信息页面
     */
    public function show(User $user)
    {
      return view('users.show',compact('user'));
    }

    /**
     * 用户注册
     */
    public function store(Request $request)
    {
        $this->validate($request,[
          'name' => 'required|max:50',
          'email' => 'required|email|unique:users|max:255',
          'password' => 'required'
        ]);

        $user = User::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => bcrypt($request->password),
        ]);

        Auth::login($user);
        session()->flash('success',"注册成功，欢迎您的到来！");
        return redirect()->route('users.show',[$user]);
    }

    /**
     * 编辑用户
     */
    public function edit(User $user)
    {
      $this->authorize('update',$user);
      return view('users.edit',compact('user'));
    }

    /**
     * 处理用户修改提交的个人信息
     */
    public function update(User $user,Request $request)
    {
      $this->validate($request,[
        'name' => 'required|max:50',
        'password' => 'nullable|confirmed|min:6'
      ]);

      $this->authorize('update',$user);

      $data = [];
      $data['name'] = $request->name;
      if($request->password){
        $data['password'] = bcrypt($request->password);
      }
      $user->update($data);

      session()->flash('success','个人资料更新成功！');

      return redirect()->route('users.show',$user->id);
    }

    /**
     * 删除用户
     */
    public function destroy(User $user)
    {
      $this->authorize('destroy',$user);
      $user->delete();
      session()->flash('success','成功删除用户!');
      return back();
    }
}
