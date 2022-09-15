<?php

/**
 * IPackageService
 *
 * @package     ScholarshipOwl\Data\Service\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	25. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Payment;

use ScholarshipOwl\Data\Entity\Payment\Package;


interface IPackageService {

	// Getting Packages Functions
	public function getPackage($packageId);
	public function getPackages();
	public function getPackagesForPayment($limit = 4);
	public function getPackagesForMobilePayment($limit = 4);
	public function getPackagesForPopup();


	// Automatic & Recurrent Functions
    public function getAutomaticPackages();
    public function getRecurrentPackages();


    // Saving Packages Functions
	public function addPackage(Package $package);
	public function updatePackage(Package $package);


	// Activation & Marking Functions
	public function activatePackage($packageId);
	public function deactivatePackage($packageId);
	public function markPackage($packageId);
	public function unmarkPackage($packageId);


	// Activation & Marking For Mobile Functions
	public function activateMobilePackage($packageId);
	public function deactivateMobilePackage($packageId);
	public function markMobilePackage($packageId);
	public function unmarkMobilePackage($packageId);
}
