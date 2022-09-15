<?php

namespace ScholarshipOwl\Domain\Repository\Association;

use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Entity\StyleEntity;
use ScholarshipOwl\Data\Service\IDDL;

class StylesAssociation
{

    /**
     * @param Package $package
     * @return Package
     */
    public function addStylesOnPackage(Package $package)
    {
        $stylesRaw = \DB::table(IDDL::TABLE_PACKAGE. ' AS p')
            ->join(IDDL::TABLE_PACKAGE_STYLE. ' AS ps', 'ps.package_id', '=', 'p.package_id')
            ->where('p.package_id', '=', $package->getPackageId())
            ->get(array('ps.*'));

        if (!empty($stylesRaw)) {

            $styles = array();
            foreach($stylesRaw as $styleRaw) {
                if ($styleRaw->element) {
                    $style = new StyleEntity($package, $styleRaw->element, $styleRaw->css, $styleRaw->content);
                    $styles[$style->getElementName()] = $style;
                }
            }

            $package->setStyles($styles);
        }

        return $package;
    }

}