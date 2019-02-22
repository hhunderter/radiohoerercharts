<?php
/**
 * @copyright Dennis Reilard
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
		'key' => 'radiohoerercharts',
        'version' => '1.1.0',
        'icon_small' => 'fa-list-ol',
        'author' => 'Reilard, Dennis',
        'link' => '',
		'official' => false,
        'languages' => [
            'de_DE' => [
                'name' => 'Radio Hörer Charts',
                'description' => 'Hier können die Hörer Charts verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Radio Listener Charts',
                'description' => 'Here you can manage your Listener Charts.',
            ],
        ],
        'ilchCore' => '2.1.20',
		'phpVersion' => '5.6',
    ];

    public function install()
    {
		$databaseConfig = new \Ilch\Config\Database($this->db());
		$databaseConfig->set('radio_hoerercharts_Guest_Allow', '0');
        $databaseConfig->set('radio_hoerercharts_showstars', '1');
		$databaseConfig->set('radio_hoerercharts_Star1', '1');
		$databaseConfig->set('radio_hoerercharts_Star2', '2');
		$databaseConfig->set('radio_hoerercharts_Star3', '3');
		$databaseConfig->set('radio_hoerercharts_Star4', '4');
		$databaseConfig->set('radio_hoerercharts_Star5', '5');

        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
		$this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Guest_Allow'");
		$this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_showstars'");
		$this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Star1'");
		$this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Star2'");
		$this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Star3'");
		$this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Star4'");
		$this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Star5'");

        $this->db()->queryMulti('	DROP TABLE IF EXISTS `[prefix]_radio_hoerercharts`;
									DROP TABLE IF EXISTS `[prefix]_radio_hoerercharts_uservotes`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_radio_hoerercharts` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `interpret` VARCHAR(255) NOT NULL,
				  `songtitel` VARCHAR(255) NOT NULL,
                  `votes` INT UNSIGNED NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
				
				CREATE TABLE IF NOT EXISTS `[prefix]_radio_hoerercharts_uservotes` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
				  `session_id` VARCHAR(255) NOT NULL DEFAULT \'\',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
			//Erste version erstellt Vorlage: https://www.ilch.de/downloads-show-1562.html
            case "1.0.0": //update zu 1.0.1
				/*
				Bugfixes
				Version-Nr angepasst
				PHPDoc überarbeitet
				Zurücksetzen mit Sicherheitsabfrage
				Englische übersetzung überarbeitet
				*/
			case "1.0.1": //update zu 1.1.0
				/*
				Bugfixes
				Gäste können wenn gewünscht auch abstimmen
				-session_id eingeführt
				*/
				$this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts_uservotes` ADD COLUMN `session_id` VARCHAR(255) NOT NULL DEFAULT \'\' AFTER `user_id`;');
				$databaseConfig = new \Ilch\Config\Database($this->db());
				$databaseConfig->set('radio_hoerercharts_Guest_Allow', '0');
        }
		return 'Update function executed.';
    }
}

