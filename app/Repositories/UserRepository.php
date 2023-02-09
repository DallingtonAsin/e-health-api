<?php 
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create($userData)
    {
        return $this->user->create($userData);
    }

    public function get($id = null)
    {
        if($id){
           return $this->user->find($id);
        }
        return $this->user->all();
    }

    public function update($id, $userData)
    {
        $user = $this->user->find($id);
        $user->update($userData);
        return $user;
    }

    public function delete($id)
    {
        $user = $this->user->find($id);
        $user->delete();
        return $user;
    }

    public function exists($id){
        $user = $this->user->where('id', $id)->exists();
        return $user; 
    }

}