<?php

/**
 * PackageService
 *
 * @package     ScholarshipOwl\Data\Service\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	07. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Payment;

use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Service\AbstractService;
use ScholarshipOwl\Domain\Repository\PackageStylesRepository;


class PackageService extends AbstractService implements IPackageService {
	const CACHE_KEY_PACKAGES_PAYMENT = "PACKAGES.PAYMENT";
	const CACHE_KEY_PACKAGES_POPUP = "PACKAGES.POPUP";
	const CACHE_KEY_PACKAGES_MOBILE_PAYMENT = "PACKAGES.MOBILE.PAYMENT";


    /**
     * @param $packageId
     * @return null|Package
     */
	public function getPackage($packageId) {
		return $this->getEntityByColumn("\\ScholarshipOwl\\Data\\Entity\\Payment\\Package", self::TABLE_PACKAGE, "package_id", $packageId);
	}

    /**
     * @return array[Package]
     */
	public function getPackages(array $ids = null) {
		$result = array();
        $where = $ids === null ? '' : sprintf('WHERE package_id IN (%s)', implode(',', $ids));

		$resultSet = $this->query(sprintf(
            "SELECT * FROM %s %s ORDER BY package_id DESC",
            self::TABLE_PACKAGE,
            $where
        ));

		foreach($resultSet as $row) {
            $package = new Package();
            $package->populate($row);
            $this->packagePlaceholdersProcessor($package);
            $result[$package->getPackageId()] = $package;
		}


		return $result;
	}

	public function getPackagesForPayment($limit = 4) {
		$result = array();

		$cacheData = $this->getFromCache(self::CACHE_KEY_PACKAGES_PAYMENT);
		if (empty($cacheData)) {
			$cacheData = array();

			$sql = sprintf("
				SELECT *
				FROM %s
				WHERE is_active = 1
				ORDER BY price ASC
				LIMIT %d
			", self::TABLE_PACKAGE, $limit);

			$resultSet = $this->query($sql);
			foreach($resultSet as $row) {
				$row = (array) $row;
				$cacheData[] = $row;

				$entity = new Package();
				$entity->populate($row);
				$this->packagePlaceholdersProcessor($entity);

				$result[$entity->getPackageId()] = $entity;
			}

			$this->setToCache(self::CACHE_KEY_PACKAGES_PAYMENT, $cacheData, 180);
		}
		else {
			foreach ($cacheData as $row) {
				$entity = new Package();
				$entity->populate($row);
				$this->packagePlaceholdersProcessor($entity);

				$result[$entity->getPackageId()] = $entity;
			}
		}

		return $result;
	}


    public function getPackagesForPaymentArranged($limit = 4) {
        $result = array();

        $cacheData = $this->getFromCache(self::CACHE_KEY_PACKAGES_PAYMENT);
        if (empty($cacheData)) {
            $cacheData = array();

            $sql = sprintf("
				SELECT *
				FROM %s
				WHERE is_active = 1
				ORDER BY price ASC
				LIMIT %d
			", self::TABLE_PACKAGE, $limit);

            $resultSet = $this->query($sql);
            foreach($resultSet as $row) {
                $row = (array) $row;
                $cacheData[] = $row;

                $entity = new Package();
                $entity->populate($row);
                $this->packagePlaceholdersProcessor($entity);

                $result[$entity->getPackageId()] = $entity;
            }

            $this->setToCache(self::CACHE_KEY_PACKAGES_PAYMENT, $cacheData, 180);
        }
        else {
            foreach ($cacheData as $row) {
                $entity = new Package();
                $entity->populate($row);
                $this->packagePlaceholdersProcessor($entity);

                $result[$entity->getPackageId()] = $entity;
            }
        }

        return $result;
    }


    public function getPackagesForMobilePayment($limit = 4) {
		$result = array();

		$cacheData = $this->getFromCache(self::CACHE_KEY_PACKAGES_MOBILE_PAYMENT);
		if (empty($cacheData)) {
			$cacheData = array();

			$sql = sprintf("
				SELECT *
				FROM %s
				WHERE is_mobile_active = 1
				ORDER BY is_mobile_marked DESC, price ASC
				LIMIT %d
			", self::TABLE_PACKAGE, $limit);

			$resultSet = $this->query($sql);
			foreach($resultSet as $row) {
				$row = (array) $row;
				$cacheData[] = $row;

				$entity = new Package();
				$entity->populate($row);
				$this->packagePlaceholdersProcessor($entity);

				$result[$entity->getPackageId()] = $entity;
			}

			$this->setToCache(self::CACHE_KEY_PACKAGES_MOBILE_PAYMENT, $cacheData, 180);
		}
		else {
			foreach ($cacheData as $row) {
				$entity = new Package();
				$entity->populate($row);
				$this->packagePlaceholdersProcessor($entity);

				$result[$entity->getPackageId()] = $entity;
			}
		}

		return $result;
	}

	public function getPackagesForPopup() {
		$result = array();

		$cacheData = $this->getFromCache(self::CACHE_KEY_PACKAGES_POPUP);
		if (empty($cacheData)) {
			$cacheData = array();

			$sql = sprintf("
				SELECT *
				FROM %s
				RIGHT JOIN popup on popup.popup_target_id = package.package_id
                WHERE popup.popup_display > 0 AND popup.popup_type = 'package'
				ORDER BY package.price ASC
			", self::TABLE_PACKAGE);

			$resultSet = $this->query($sql);
			foreach($resultSet as $row) {
				$row = (array) $row;
				$cacheData[] = $row;

				$entity = new Package();
				$entity->populate($row);
				$this->packagePlaceholdersProcessor($entity);

				$result[$entity->getPackageId()] = $entity;
			}

			$this->setToCache(self::CACHE_KEY_PACKAGES_POPUP, $cacheData, 180);
		}
		else {
			foreach ($cacheData as $row) {
				$entity = new Package();
				$entity->populate($row);
				$this->packagePlaceholdersProcessor($entity);

				$result[$entity->getPackageId()] = $entity;
			}
		}

		return $result;
	}

    public function getAutomaticPackages() {
        $result = array();
        $sql = sprintf("SELECT * FROM %s WHERE is_automatic = 1", self::TABLE_PACKAGE);

        $resultSet = $this->query($sql);
        foreach($resultSet as $row) {
            $entity = new Package();
            $entity->populate($row);
            $this->packagePlaceholdersProcessor($entity);

            $result[$entity->getPackageId()] = $entity;
        }

        return $result;
    }

    public function getRecurrentPackages() {
        $result = array();
        $sql = sprintf("SELECT * FROM %s WHERE expiration_type = '%s' AND is_active = 1", self::TABLE_PACKAGE, Package::EXPIRATION_TYPE_RECURRENT);

        $resultSet = $this->query($sql);
        foreach($resultSet as $row) {
            $entity = new Package();
            $entity->populate($row);
            $this->packagePlaceholdersProcessor($entity);

            $result[$entity->getPackageId()] = $entity;
        }

        return $result;
    }

	public function addPackage(Package $package) {
		return $this->savePackage($package, true);
	}

	public function updatePackage(Package $package) {
		return $this->savePackage($package, false);
	}

	public function activatePackage($packageId) {
		return $this->toggleActivation($packageId, 1);
	}

	public function deactivatePackage($packageId) {
		return $this->toggleActivation($packageId, 0);
	}

	public function markPackage($packageId) {
		return $this->toggleMark($packageId, 1);
	}

	public function unmarkPackage($packageId) {
		return $this->toggleMark($packageId, 0);
	}

	public function activateMobilePackage($packageId) {
		return $this->toggleMobileActivation($packageId, 1);
	}

	public function deactivateMobilePackage($packageId) {
		return $this->toggleMobileActivation($packageId, 0);
	}

	public function markMobilePackage($packageId) {
		return $this->toggleMobileMark($packageId, 1);
	}

	public function unmarkMobilePackage($packageId) {
		return $this->toggleMobileMark($packageId, 0);
	}


	private function savePackage(Package $package, $insert = true) {
		$result = 0;

		$packageId = $package->getPackageId();
		$data = $package->toArray();

		unset($data["package_id"]);
		unset($data["is_marked"]);
		unset($data["is_mobile_marked"]);

		if($insert == true) {
			$this->insert(self::TABLE_PACKAGE, $data);
			$packageId = $this->getLastInsertId();
            $package->setPackageId($packageId);

			$result = $packageId;
		}
		else {
			unset($data["package_id"]);
			$result = $this->update(self::TABLE_PACKAGE, $data, array("package_id" => $packageId));
		}

        $packageStylesRepository = new PackageStylesRepository();
        $packageStylesRepository->saveStyles($package);

		$this->setToCache(self::CACHE_KEY_PACKAGES_PAYMENT, null);
		$this->setToCache(self::CACHE_KEY_PACKAGES_MOBILE_PAYMENT, null);
        \Cache::tags([\App\Entity\Package::CACHE_TAG])->flush();
		return $result;
	}


	private function toggleActivation($packageId, $value) {
		$result = 0;

		$sql = sprintf("UPDATE %s SET is_active = $value WHERE package_id = ?", self::TABLE_PACKAGE);
		$result = $this->execute($sql, array($packageId));
		$this->setToCache(self::CACHE_KEY_PACKAGES_PAYMENT, null);

		return $result;
	}

	private function toggleMobileActivation($packageId, $value) {
		$result = 0;

		$sql = sprintf("UPDATE %s SET is_mobile_active = $value WHERE package_id = ?", self::TABLE_PACKAGE);
		$result = $this->execute($sql, array($packageId));
		$this->setToCache(self::CACHE_KEY_PACKAGES_MOBILE_PAYMENT, null);

		return $result;
	}


	private function toggleMark($packageId, $value) {
		$result = 0;

		try {
			$this->beginTransaction();

			$sql = sprintf("UPDATE %s SET is_marked = 0", self::TABLE_PACKAGE);
			$this->execute($sql);

			$sql = sprintf("UPDATE %s SET is_marked = $value WHERE package_id = ?", self::TABLE_PACKAGE);
			$result = $this->execute($sql, array($packageId));

			$this->commit();

			$this->setToCache(self::CACHE_KEY_PACKAGES_PAYMENT, null);
		}
		catch(\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}

	private function toggleMobileMark($packageId, $value) {
		$result = 0;

		try {
			$this->beginTransaction();

			$sql = sprintf("UPDATE %s SET is_mobile_marked = 0", self::TABLE_PACKAGE);
			$this->execute($sql);

			$sql = sprintf("UPDATE %s SET is_mobile_marked = $value WHERE package_id = ?", self::TABLE_PACKAGE);
			$result = $this->execute($sql, array($packageId));

			$this->commit();

			$this->setToCache(self::CACHE_KEY_PACKAGES_MOBILE_PAYMENT, null);
		}
		catch(\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}

	public function getUserPackage($accountId){
		$package = null;
		$sql = sprintf("select p.* from %s as p inner join %s as s on p.package_id = s.package_id where s.account_id = ?", self::TABLE_PACKAGE, self::TABLE_SUBSCRIPTION);
		$resultSet = $this->query($sql, array($accountId));
		if(!empty($resultSet)){
			$package = new Package();
			$package->populate($resultSet[0]);
		}
		return $package;
	}

	/**
     * List all available placeholders
     *
     * @param bool $evaluate If true, then placeholders' value will be provided too (if it's possible)
     * @return array
     */
    public static function placeholderList(bool $evaluate = false): array
    {
        return [
            'ACTIVE_AWARDS_TOTAL_PRICE' => [
                'description' => 'The total price of active and not expired scholarship awards currently available.',
                'value' => $evaluate ? self::placeholderEvaluate('ACTIVE_AWARDS_TOTAL_PRICE') : null
            ],
            'ACTIVE_AWARDS_TOTAL_PRICE_PER_MONTH' => [
                'description' => 'The total price of active and not expired scholarship awards per month
                                  (previous full month by package Start Date).',
                'value' => $evaluate ? self::placeholderEvaluate('ACTIVE_AWARDS_TOTAL_PRICE_PER_MONTH') : null
            ]
        ];
    }

    /**
     * Checks if a particular placeholder (one or more) present in a text
     *
     * @param string $text
     * @param string $phKey
     * @return bool
     */
    private static function hasPlaceholder(string $text, string $phKey): bool
    {
        return strpos($text, "*|{$phKey}|*") !== false;
    }

    /**
     * Resolves a placeholder value.
     *
     * @param string $phKey
     * @return null|string
     */
    private static function placeholderEvaluate(string $phKey)
    {
        $val = null;

        /** @var ScholarshipRepository $repo */
        $repo = \EntityManager::getRepository(Scholarship::class);
        if ($phKey === 'ACTIVE_AWARDS_TOTAL_PRICE') {
            $val = $repo->totalPriceOfActiveAndNotExpired();
            $val = number_format($val, 0, ',', ',');
        }
        else if ($phKey === 'ACTIVE_AWARDS_TOTAL_PRICE_PER_MONTH')
        {
            $val = $repo->totalPriceOfActiveAndNotExpiredPerMonth();
            $val = number_format($val, 0, ',', ',');
        }

        return $val;
    }

    /**
     * Replaces placeholder with its corresponding value.
     *
     * @param string $phKey
     * @param $phVal
     * @param string $haystack
     * @return mixed
     */
    private static function placeholderInterpolate(string $phKey, $phVal, string $haystack)
    {
        return str_replace("*|{$phKey}|*", $phVal, $haystack);
    }

	/**
     * Interpolates placeholders' values. So some package fields may contained predefined placeholders
     * which will be replaced with it's corresponding values.
     *
     * @param $package
     */
    public function packagePlaceholdersProcessor($package)
    {
        foreach (self::placeholderList() as $phKey => $phData) {
            $targetParts = [
                'description',
                'message',
                'successMessage'
            ];

            foreach ($targetParts as $partName) {
                $getter = 'get'.ucfirst($partName);
                $setter = 'set'.ucfirst($partName);
                $text = $package->$getter();

                if ($text && self::hasPlaceholder($text, $phKey) !== false) {
                    $phVal = $this->placeholderEvaluate($phKey);
                    $interpolatedText = $this->placeholderInterpolate($phKey, $phVal, $text);
                    $package->$setter($interpolatedText);
                }
            }
        }
    }
}
