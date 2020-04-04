<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     // $this->middleware('auth');
    // }

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortProp = $request['sortProp'];
        $sortOrder = $request['sortOrder'];

        if($sortProp != 'undefined' && $sortOrder != 'undefined'){
            if($sortOrder === 'descending') $sortOrder = 'desc';
            else $sortOrder = 'asc';
        }
        else{
            $sortProp = 'id';
            $sortOrder = 'asc';
        }

        if($request['showDeleted'] == 'true'){
            return User::onlyTrashed()->
                where(function($query) use ($request){
                    $query->where('firstname','like','%'.$request['search'].'%')
                        ->orWhere('lastname','like','%'.$request['search'].'%')
                        ->orWhere('username','like','%'.$request['search'].'%')
                        ->orWhere('email','like','%'.$request['search'].'%');
                })
                ->orderBy($sortProp, $sortOrder)
                ->paginate($request['take']);
        }
        else{
            return User::where(function($query) use ($request){
                    $query->where('firstname','like','%'.$request['search'].'%')
                        ->orWhere('lastname','like','%'.$request['search'].'%')
                        ->orWhere('username','like','%'.$request['search'].'%')
                        ->orWhere('email','like','%'.$request['search'].'%');
                })
                ->orderBy($sortProp, $sortOrder)
                ->paginate($request['take']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string|max:191|unique:users',
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'address' => 'required|string|max:191',
            'postcode' => 'required|string|max:191',
            'contact' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'role' => 'required'
        ]);

        return User::create([
            'username' => $request['username'],
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'],
            'password' => Hash::make($request['username']),
            'address' => $request['address'],
            'postcode' => $request['postcode'],
            'contact' => $request['contact'],
            'email' => $request['email'],
            'role' => $request['role'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $user->makeHidden(['password', 'created_at', 'updated_at', 'deleted_at']);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'username' => 'required|string|max:191|unique:users,username,'.$id.',id',
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'address' => 'required|string|max:191',
            'postcode' => 'required|string|max:191',
            'contact' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$id.',id',
            'role' => 'required'
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());

        return ['message' => 'User details updated'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return ['message' => 'User deleted'];
    }

    public function multipleDeleteUsers(Request $request)
    {
        $user_ids = $request['user_ids'];

        if($request['is_restore'] == 'true') User::whereIn('id', $user_ids)->restore();
        else User::whereIn('id', $user_ids)->delete();

        return ['message' => 'User/s successfully deleted.'];
    }
}
