<?php

namespace App\Interfaces;

interface AdminRepositoryInterface
{
    public function getDashboardStats();
    public function getRecentPatients($limit = 10);
    public function getRecentVisits($limit = 10);
    public function getAllUsers($paginate = 20);
    public function createUser(array $data);
    public function updateUser($id, array $data);
    public function deleteUser($id);
    public function getAllRoles();
    public function createRole(array $data);
    public function updateRole($id, array $data);
    public function deleteRole($id);
    public function getAllPermissions();
}