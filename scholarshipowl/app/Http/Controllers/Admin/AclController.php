<?php namespace App\Http\Controllers\Admin;

use App\Entity\Account as EntityAccount;
use App\Entity\Admin\Admin;
use App\Entity\Admin\AdminRole;
use App\Entity\Country;
use App\Entity\Domain;
use App\Policies\RoutePolicy;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;

use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Account\AccountStatus;
use ScholarshipOwl\Data\Entity\Account\AccountType;
use ScholarshipOwl\Data\Entity\Account\Profile;
use App\Services\Account\Exception\EmailAlreadyRegisteredException;

class AclController extends BaseController
{

    /**
     * @var \App\Services\Account\AccountService
     */
    protected $accountService;


    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * AclController constructor.
     */
    public function __construct(\App\Services\Account\AccountService $accountService, EntityManager $em)
    {
        parent::__construct();

        $this->addBreadcrumb('Access Limiter', 'acl.admins');
        $this->accountService = $accountService;
        $this->em = $em;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function adminsAction()
    {
        return $this->view('Access Limiter - Index', 'admin.acl.admins', [
            'admins' => \EntityManager::getRepository(Admin::class)->findAll(),
        ]);
    }

    /**
     * @param null $adminId
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function adminAction($adminId = null)
    {
        $this->addPostBreadcrumb('acl.admin', $adminId);

        $roleOptions = [];
        /** @var AdminRole $adminRole */
        foreach (\EntityManager::getRepository(AdminRole::class)->findAll() as $adminRole) {
            $roleOptions[$adminRole->getAdminRoleId()] = $adminRole->getName();
        }

        return $this->view('Access Limiter - Admin', 'admin.acl.admin', [
            'admin' => $adminId ? \EntityManager::find(Admin::class, $adminId) : null,
            'options' => [
                'role' => $roleOptions,
                'status' => [
                    Admin::STATUS_ACTIVE => ucfirst(Admin::STATUS_ACTIVE),
                    Admin::STATUS_DISABLED => ucfirst(Admin::STATUS_DISABLED),
                ],
            ],
        ]);
    }

    /**
     * @param Request $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function adminPostAction(Request $request)
    {
        $this->validate($request, [
            'name'   => 'required',
            'email'  => 'required|email',
            'role'   => 'required',
            'status' => 'required'
        ]);

        /** @var AdminRole $adminRole */
        $adminRole = \EntityManager::findById(AdminRole::class, $request->get('role'));

        if ($adminId = $request->get('adminId', false)) {
            /** @var Admin $admin */
            $admin = \EntityManager::findById(Admin::class, $adminId);
            $admin->setName($request->get('name'));
            $admin->setEmail($request->get('email'));
            $admin->setStatus($request->get('status'));
            $admin->setAdminRole($adminRole);

            if ($request->has('password')) {
                $admin->setHashPassword($request->get('password'));
            }

            \EntityManager::flush($admin);
            \Session::flash('message', "Admin saved!");
        } else {
            $admin = new Admin(
                $request->get('name'),
                $request->get('email'),
                $request->get('status'),
                $request->get('password'),
                $adminRole
            );

            try {
                $account = $this->accountService->register(
                    $admin->getName(),
                    $admin->getName(),
                    $admin->getEmail(),
                    null
                );
                $account->setDomain(Domain::SCHOLARSHIPOWL);
                $account->setAccountStatus(\App\Entity\AccountStatus::ACTIVE);
                $account->setAccountType(\App\Entity\AccountType::USER);
                $account->setPassword($request->get('password'));
                $account->getProfile()->setCountry(Country::findByCountryCode(Country::getCountryCodeByIP()));

                $this->em->persist($account);
                $this->em->flush();

            } catch (UniqueConstraintViolationException $e) {
                return \Redirect::route('admin::acl.admin')->withErrors(
                    sprintf("Account with email '%s' already exists", $admin->getEmail())
                );
            }

            $admin->setAccount($account);

            \EntityManager::persist($admin);
            \EntityManager::flush($admin);
            \Session::flash('message', "Admin created!");
        }

        return \Redirect::route('admin::acl.admin', $admin->getAdminId());
    }

    /**
     * @param $adminId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Facades\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function adminDeleteAction($adminId)
    {
        /** @var Admin $admin */
        $admin = \EntityManager::findById(Admin::class, $adminId);
        \EntityManager::remove($admin);
        \EntityManager::flush($admin);

        $account = $this->accountService->deleteAccount($admin->getAccount()->getAccountId());
        \Session::flash('message', sprintf('Admin %s deleted', $admin->getName()));

        return \Redirect::route('admin::acl.admins');
    }

    /**
     * Show all roles
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function rolesAction()
    {
        return $this->view('Access Limiter - Roles', 'admin.acl.roles', [
            'roles' => \EntityManager::getRepository(AdminRole::class)->findAll(),
        ]);
    }

    /**
     * Role view and edit
     *
     * @param null $roleId
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function roleAction($roleId = null)
    {
        $this->addPostBreadcrumb('acl.role', $roleId);

        return $this->view('Access Limiter - Role', 'admin.acl.role', [
            'role' => $roleId ? \EntityManager::find(AdminRole::class, $roleId) : null,
            'options' => [
                'access_levels' => [
                    '' => '--- Select ---',
                    AdminRole::LEVEL_ACCESS_FULL => 'Full data access',
                    AdminRole::LEVEL_ACCESS_RESTRICTED => 'Restricted data access',
                    AdminRole::LEVEL_ACCESS_NO_DATA => 'No data access'
                ]
            ]
        ]);
    }

    /**
     * Save or create admin role
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rolePostAction(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($request->has('roleId')) {
            $role = \EntityManager::find(AdminRole::class, $request->get('roleId'));
            $role->setName($request->get('name'));
            $role->setDescription($request->get('description'));
            $role->setAccessLevel($request->get('access_level'));
            \EntityManager::flush($role);
            \Session::flash('message', 'Role saved!');
        } else {
            $role = new AdminRole($request->get('name'), $request->get('description'), $request->get('access_level'));

            \EntityManager::persist($role);
            \EntityManager::flush($role);
            \Session::flash('message', 'Role created!');
        }


        return \Redirect::route('admin::acl.role', $role->getAdminRoleId());
    }

    /**
     * Delete role
     *
     * @param $roleId
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */

    public function roleDeleteAction($roleId)
    {
        /** @var AdminRole $role */
        if (null === ($role = \EntityManager::find(AdminRole::class, $roleId))) {
            throw new \Exception(sprintf('Admin with (%s) not found'), $roleId);
        }

        if (count(\EntityManager::getRepository(Admin::class)->findBy(['adminRole' => $role]))) {
            return \Redirect::to($this->getRedirectUrl())->withErrors([
                sprintf("Some admins using role '%s', please change admins role before delete it.", $role->getName())
            ]);
        }

        \EntityManager::remove($role);
        \EntityManager::flush();
        \Session::flash('message', sprintf('Role %s deleted', $role->getName()));

        return \Redirect::route('admin::acl.roles');
    }

    /**
     * @param $roleId
     *
     * @return \Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function rolePermissionsAction($roleId)
    {
        /** @var AdminRole $role */
        if (null === ($role = \EntityManager::find(AdminRole::class, $roleId))) {
            throw new \Exception(sprintf('Admin with (%s) not found'), $roleId);
        }

        $title = sprintf('%s permissions', $role->getName());
        $this->addBreadcrumb($title, 'acl.permissions', ['id' => $roleId]);
        return $this->view($title, 'admin.acl.permissions', ['role' => $role]);
    }

    /**
     * @param $roleId
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function rolePermissionsPostAction($roleId)
    {
        /** @var AdminRole $role */
        if (null === ($role = \EntityManager::find(AdminRole::class, $roleId))) {
            throw new \Exception(sprintf('Admin with (%s) not found'), $roleId);
        }

        foreach (array_keys(RoutePolicy::getAvailablePermissions()) as $permission) {
            if (\Request::get(str_replace('.', '_', $permission), false)) {
                $role->addPermission($permission);
            } else {
                $role->removePermission($permission);
            }
        }

        if(\Request::get("account::onboarding-call_view", false)){
            $role->addPermission("account::onboarding-call.view");
        }else{
            $role->removePermission("account::onboarding-call.view");
        }

        if(\Request::get("account::onboarding-call_update", false)){
            $role->addPermission("account::onboarding-call.update");
        }else{
            $role->removePermission("account::onboarding-call.update");
        }

        \EntityManager::flush();
        \Session::flash('message', "Permissions saved");

        return \Redirect::route('admin::acl.permissions', $roleId);
    }
}
