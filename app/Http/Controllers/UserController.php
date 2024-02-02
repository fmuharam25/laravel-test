<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserSubsRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->input('showdata')) {
                $user =  User::orderBy('created_at', 'desc')->get();
                return UserResource::collection($user);
            }

            $columns = ['name', 'email', 'created_at'];
            $length = $request->input('length');
            $column = $request->input('column');
            $search_input = $request->input('search');

            $query = User::select('name', 'email', 'created_at')->orderBy($columns[$column]);

            if ($search_input) {
                $query->where(function ($query) use ($search_input) {
                    $query->where('name', 'like', '%' . $search_input . '%')
                        ->orWhere('email', 'like', '%' . $search_input . '%')
                        ->orWhere('created_at', 'like', '%' . $search_input . '%');
                });
            }
            $user = $query->paginate($length);

            return UserResource::collection($user);
        } catch (Exception $e) {

            return response()->json(['message' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create($request->all());
            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        try {
         
            $user = User::find($id);
            $user->update([
                'is_subscribe' => $request->is_subscribe
            ]);
            return response()->json(['data' => $user, 'message' => 'Update Success']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {

            $user->delete();
            return response()->json(['message' => 'Product deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
