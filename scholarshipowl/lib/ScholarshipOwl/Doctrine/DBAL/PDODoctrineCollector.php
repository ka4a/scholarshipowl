<?php namespace ScholarshipOwl\Doctrine\DBAL;

use DebugBar\DataCollector\PDO\PDOCollector;

class PDODoctrineCollector extends PDOCollector {
    /**
     * {@inheritDoc}
     */
    public function getName() {
        return 'pdo-doctrine';
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgets()
    {
        return array(
            "database" => array(
                "widget" => "PhpDebugBar.Widgets.SQLQueriesWidget",
                "map" => $this->getName(),
                "default" => "[]"
            ),
            "database:badge" => array(
                "map" => $this->getName().".nb_statements",
                "default" => 0
            )
        );
    }
}
