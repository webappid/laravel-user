<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Tests\Unit\Repositories;


use WebAppId\User\Repositories\Requests\RoleRepositoryRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\User\Models\Role;
use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Tests\TestCase;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 19/04/20
 * Time: 10.40
 * Class RoleRepositoryTest
 * @package WebAppId\User\Tests\Unit\Repositories
 */
class RoleRepositoryTest extends TestCase
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->roleRepository = $this->container->make(RoleRepository::class);
        } catch (BindingResolutionException $e) {
            dd($e);
            report($e);
        }
    }

    public function getDummyData(int $no = 0): ?RoleRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(RoleRepositoryRequest::class);
            $dummy->name = $this->getFaker()->text(255);
            $dummy->description = $this->getFaker()->text(255);

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?Role
    {
        $roleRepositoryRequest = $this->getDummyData($no);
        $result = $this->container->call([$this->roleRepository, 'store'], ['roleRepositoryRequest' => $roleRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $role = $this->testStore();
        $result = $this->container->call([$this->roleRepository, 'getById'], ['id' => $role->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $role = $this->testStore();
        $result = $this->container->call([$this->roleRepository, 'delete'], ['id' => $role->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $resultList = $this->container->call([$this->roleRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testUpdate()
    {
        $role = $this->testStore();
        $roleRepositoryRequest = $this->getDummyData(1);
        $result = $this->container->call([$this->roleRepository, 'update'], ['id' => $role->id, 'roleRepositoryRequest' => $roleRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }
}
