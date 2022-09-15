<?php

namespace ScholarshipOwl\Domain\Repository;


use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Entity\StyleEntity;
use ScholarshipOwl\Data\Service\IDDL;

class PackageStylesRepository
{

    /**
     * @param Package $package
     */
    public function saveStyles(Package $package)
    {
        $styles = $package->getStyles();

        if (!empty($styles) && is_array($styles)) {

            \DB::statement(
                'INSERT INTO ' . IDDL::TABLE_PACKAGE_STYLE . ' (`package_id`, `element`, `content`, `css`) VALUES ' .
                    implode(',', array_map(function(StyleEntity $style) use ($package) {
                        return '(' . implode(',', array(
                            $package->getPackageId(),
                            "'" . $style->getElementName() . "'",
                            "'" . $style->getContent() . "'",
                            "'" . $style->getCSS() . "'",
                        )) . ')';
                    }, $styles)) .
                'ON DUPLICATE KEY UPDATE `css` = VALUES(`css`), `content` = VALUES(`content`);'
            );

        }
    }

}