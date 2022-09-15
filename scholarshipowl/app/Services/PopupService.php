<?php
namespace App\Services;

use App\Entity\Popup;
use App\Entity\Repository\PopupRepository;
use App\Services\Marketing\RedirectRulesService;
use Doctrine\ORM\EntityManager;


class PopupService
{
	/**
	 * @var EntityManager
	 */
 	protected $em;

    /**
     * @var PopupRepository
     */
 	protected $popupRepo;

    /**
     * PopupService constructor.
     *
     * @param EntityManager    $entityManager
     */
	public function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
		$this->popupRepo = $this->em->getRepository(\App\Entity\Popup::class);
	}

    /**
     * Load all popup for filled page url and for current user
     * @param string $pageUrl
     * @param integer $accountId
     *
     * @return array|Popup[]
     */
	public function getPopupsByPage($pageUrl, $accountId)
	{
        $pageUrl = '/' . trim($pageUrl, '/');

        $result = [];
        $popups = $this->popupRepo->getAllByUrl($pageUrl);
        $popups = $this->checkRedirectRulesForPopup($accountId, $popups);

        /** @var Popup $popup */
        foreach ($popups as $popup) {
            $result[$popup->getPopupId()] = $popup;
        }

        return empty($result) ? [] : [array_shift($result)];
	}

    /**
     * Return popups list
     * @return array
     */
    public function getPopups()
    {
        return $this->popupRepo->findAll();
    }

    /**
     * Check and add to all popups redirect rules if theirs exist
     * @param $accountId
     * @param $popups
     *
     * @return mixed
     */
    protected function checkRedirectRulesForPopup($accountId, $popups)
    {
        /**
         * @var Popup $popup
         */
        $service = new RedirectRulesService($this->em);

        return array_filter($popups, function (Popup $popup) use ($accountId, $service) {
            if ($popup->getRuleSet()) {
                return $service->checkUserAgainstRules(
                    $popup->getRuleSet()->getId(), $accountId
                );
            }

            return true;
        });
    }
}
