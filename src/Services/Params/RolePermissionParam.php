<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/6/2019
 * Time: 12:55 PM
 */

namespace WebAppId\User\Services\Params;

/**
 * Class RolePermissionParam
 * @package WebAppId\User\Services\Params
 */
class RolePermissionParam
{
    private $id;
    private $role_id;
    private $permission_id;
    private $created_by;
    private $updated_by;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    /**
     * @param mixed $role_id
     */
    public function setRoleId($role_id): void
    {
        $this->role_id = $role_id;
    }

    /**
     * @return int|null
     */
    public function getPermissionId(): ?int
    {
        return $this->permission_id;
    }

    /**
     * @param mixed $permission_id
     */
    public function setPermissionId($permission_id): void
    {
        $this->permission_id = $permission_id;
    }

    /**
     * @return int|null
     */
    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    /**
     * @param mixed $created_by
     */
    public function setCreatedBy($created_by): void
    {
        $this->created_by = $created_by;
    }

    /**
     * @return int|null
     */
    public function getUpdatedBy(): ?int
    {
        return $this->updated_by;
    }

    /**
     * @param mixed $updated_by
     */
    public function setUpdatedBy($updated_by): void
    {
        $this->updated_by = $updated_by;
    }
}
