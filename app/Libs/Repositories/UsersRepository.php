<?php


namespace App\Libs\Repositories;


use App\Libs\Interfaces\UserInterface;
use App\User;

class UsersRepository extends DefaultRepository implements UserInterface
{


    /**
     * UsersRepository constructor.
     */
    public function __construct()
    {
    }

    public function getItem($id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return User::where('id', '=', $id)
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->first();
    }

    public function getItemBy($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return User::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->first();
    }

    public function getAll($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return User::with('region', 'role')->where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->get();
    }

    public function getAllPaginated($pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return User::with('region', 'role')->where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->paginate($pagination_size);
    }

    public function addNew($inputData)
    {
        $newUser = new User();
        $newUser->role_id = isset($inputData['role_id']) ? $inputData['role_id'] : null;
        $newUser->full_name = isset($inputData['full_name']) ? $inputData['full_name'] : null;
        $newUser->email = isset($inputData['email']) ? $inputData['email'] : null;
        $newUser->phone = isset($inputData['phone']) ? $inputData['phone'] : null;
        $newUser->region_id = isset($inputData['region_id']) ? $inputData['region_id'] : null;
        $newUser->call_center = isset($inputData['call_center']) ? $inputData['call_center'] : null;
        $newUser->password = isset($inputData['password']) ? bcrypt($inputData['password']) : bcrypt($inputData['password']);
        $newUser->save();
        return $newUser;
    }

    public function updateItem($id, $updateData, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return User::where('id', '=', $id)->where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->update($updateData);
    }

    public function updateItemBy($queryData, $updateData)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return User::where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->update($updateData);
    }

    public function deleteItem($id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return User::where('id', '=', $id)->where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->delete();
    }

    public function deleteItemBy($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        $usersForDelete = User::where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->get();
        foreach ($usersForDelete as $user) {
            if ($user instanceof User) {
                $user->delete();
            }
        }
        return true;
    }
}
