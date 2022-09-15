<?php
# CrEaTeD bY FaI8T IlYa      
# 2017  

namespace App\Http\Controllers\Admin;

use App\Entity\ApplyMe\ApplymeSettings;
use App\Entity\PushNotifications;
use Illuminate\Http\Request;
use ScholarshipOwl\Http\ViewModel;
use Doctrine\ORM\EntityManager;

class ApplyMeController extends BaseController
{
	/**
	 * @var EntityManager
	 */
	protected $em;

	/**
	 * @var \Doctrine\ORM\EntityRepository
	 */
	protected $applyMeSettingsRepo;

	/**
	 * ApplyMeController constructor.
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		parent::__construct();
		$this->em = $entityManager;
		$this->applyMeSettingsRepo = $this->em->getRepository(ApplymeSettings::class);
	}

	/**
	 * @return \Illuminate\Contracts\View\View
	 */
	public function getSettings()
	{
		$this->addBreadcrumb("Settings", 'applyme.settings.index');
		$settings = $this->applyMeSettingsRepo->findAll();

		return $this->view('Settings', 'admin.apply-me.index', ['settings' => $settings]);
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveSettings(Request $request)
	{
		$this->validate($request, [
			'swipes_per_day' => 'digits_between:0,1000|required'
		]);

		foreach ($request->all() as $key => $value) {
			if($key == 'swipes_per_day') {
				$setting = $this->applyMeSettingsRepo->findOneBy(['name' => $key]);
				$setting->setValue($value);
			}
		}

		\EntityManager::flush();

		return \Redirect::route('admin::applyme.settings.index')->with([
			'message' => 'Saved!',
		]);
	}
}