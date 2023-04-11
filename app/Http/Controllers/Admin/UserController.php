<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dataInsert=[
            'name' => $request->nameUser,
            'email' => $request->email,
            'password' => Hash::make($request->pass),
            'status' => $request->status,
        ];
        //print_r($dataInsert);
        //die;

        $user = User::create($dataInsert);
        $user->assignRole($request->role);
        return redirect()->route('admin.users.index')->with('message','Create user successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roleName=$user->getRoleNames()->first();
        $roles = Role::all();
        return view('admin.users.edit', compact('roles','user','roleName'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request,User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if(!empty($request->pass)){
            $user->password = Hash::make($request->pass);

        }
        $user->name = $request->nameUser;
        $user->email = $request->email;
        $user->status = $request->status;
        if($user->save()){
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            $user->assignRole($request->role);
            return redirect()->back()->with('message','User updated successfully');
        }

        return redirect()->back()->with('error','Whoops!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::findOrFail($id);

        if ($user->delete()){
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            return redirect()->back()->with('message','User deleted successfully');
        }
        return redirect()->back()->with('message','Whoops!!');
    }

    public function banUnban($id, $status)
    {
        if (auth()->user()->hasRole('Admin')){
            $user = User::findOrFail($id);
            $user->status = $status;
            if ($user->save()){
                return redirect()->back()->with('message', 'User status updated successfully!');
            }
            return redirect()->back()->with('error', 'User status update fail!');
        }
        return redirect(Response::HTTP_FORBIDDEN, '403 Forbidden');
    }
}
