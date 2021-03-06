<?php
/**
 * @copyright Dennis Reilard alias hhunderter
 * @package ilch
 */

namespace Modules\RadioHoererCharts\Config;

use Modules\RadioHoererCharts\Mappers\HoererCharts as HoererChartsMapper;
use Modules\RadioHoererCharts\Mappers\HoererChartsList as HoererChartsListMapper;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'radiohoerercharts',
        'version' => '1.6.1',
        'icon_small' => 'fa-list-ol',
        'author' => 'Reilard, Dennis alias hhunderter ',
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
        'ilchCore' => '2.1.41',
        'phpVersion' => '7.0',
    ];

    public function install()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('radio_hoerercharts_Guest_Allow', '0');
        $databaseConfig->set('radio_hoerercharts_Start_Datetime', '');
        $databaseConfig->set('radio_hoerercharts_End_Datetime', '');
        $databaseConfig->set('radio_hoerercharts_Program_Name', 'Hörercharts');
        $databaseConfig->set('radio_hoerercharts_showstars', '1');
        $databaseConfig->set('radio_hoerercharts_Star1', '1');
        $databaseConfig->set('radio_hoerercharts_Star2', '2');
        $databaseConfig->set('radio_hoerercharts_Star3', '3');
        $databaseConfig->set('radio_hoerercharts_Star4', '4');
        $databaseConfig->set('radio_hoerercharts_Star5', '5');
        $databaseConfig->set('radio_hoerercharts_all_sec_vote', '86400'); // 24h
        $databaseConfig->set('radio_hoerercharts_allow_suggestion', '1');
        $databaseConfig->set('radio_hoerercharts_Program_sec_duration', '7200'); // 2h
        $databaseConfig->set('radio_hoerercharts_votetext_de', '[size=120][b]So funktioniert es:[/b][/size]
--user-- darf für seinen Lieblingstitel stimmen und an einem Bestimmten Tag (siehe: Sendeplan) wird es die Sendung "--name--" geben.
In dieser Sendung wird von unten (z.B. Platz 20) bis auf Platz 1 gespielt.
Danach werden die Stimmen von Ihnen wieder gelöscht und die Chartliste aktualisiert, d.h. es werden möglicherweise neue Interpreten hinzugefügt oder ältere Titel werden ausgeblendet usw.');
        $databaseConfig->set('radio_hoerercharts_votetext_en', '[b][size=120]So It Works:[/size][/b]
--user-- can vote for their favorite song and on a specific day (see: Broadcasting schedule) there will be the show "--name--".
This show plays from the bottom (e.g. 20th place) to the first place.
After that, the votes will be deleted again and updates the chart list, i.e. new artists may be added or older titles are hidden, etc.');
        $databaseConfig->set('radio_hoerercharts_show_artwork', '0');
        $databaseConfig->set('radio_hoerercharts_active_list', '1');

        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Guest_Allow'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Start_Datetime'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_End_Datetime'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Program_Name'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_showstars'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Star1'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Star2'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Star3'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Star4'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Star5'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_all_sec_vote'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_allow_suggestion'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_Program_sec_duration'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_votetext_de'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_votetext_en'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_show_artwork'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'radio_hoerercharts_active_list'");

        $this->db()->queryMulti('    DROP TABLE IF EXISTS `[prefix]_radio_hoerercharts`;
                                    DROP TABLE IF EXISTS `[prefix]_radio_hoerercharts_uservotes`;
                                    DROP TABLE IF EXISTS `[prefix]_radio_hoerercharts_suggestion`;
                                    DROP TABLE IF EXISTS `[prefix]_radio_hoerercharts_list`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_radio_hoerercharts` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `setfree` TINYINT(1) NOT NULL DEFAULT \'1\',
                  `interpret` VARCHAR(255) NOT NULL,
                  `songtitel` VARCHAR(255) NOT NULL,
                  `votes` INT UNSIGNED NOT NULL,
                  `datecreate` DATETIME NOT NULL,
                  `user_id` INT(11) NOT NULL,
                  `artworkUrl` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_radio_hoerercharts_uservotes` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `session_id` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `last_activity` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_radio_hoerercharts_suggestion` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `interpret` VARCHAR(255) NOT NULL,
                  `songtitel` VARCHAR(255) NOT NULL,
                  `datecreate` DATETIME NOT NULL,
                  `user_id` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
                
                CREATE TABLE IF NOT EXISTS `[prefix]_radio_hoerercharts_list` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `hid` int(11) NOT NULL,
                  `list` tinyint(1) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            // Erste version erstellt Vorlage: https://www.ilch.de/downloads-show-1562.html
            case "1.0.0":
            // update zu 1.0.1
                /*
                Bugfixes
                Version-Nr angepasst
                PHPDoc überarbeitet
                Zurücksetzen mit Sicherheitsabfrage
                Englische übersetzung überarbeitet
                */
            case "1.0.1":
            // update zu 1.1.0
                /*
                Bugfixes
                Gäste können wenn gewünscht auch abstimmen
                -session_id eingeführt
                */
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts_uservotes` ADD COLUMN `session_id` VARCHAR(255) NOT NULL DEFAULT \'\' AFTER `user_id`;');
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('radio_hoerercharts_Guest_Allow', '0');
            case "1.1.0":
            // update zu 1.2.0
                /*
                Kleine Verbesserungen
                Icon geändert
                Wenn gewünscht kann ein Abstimmungszeitraum gewählt werden
                Programmname kann geändert werden
                */
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('radio_hoerercharts_Start_Datetime', '');
                $databaseConfig->set('radio_hoerercharts_End_Datetime', '');
                $databaseConfig->set('radio_hoerercharts_Program_Name', 'Hörercharts');
                $fields = [
                    'author' => 'Reilard, Dennis alias hhunderter ',
                    'icon_small' => 'fa-list-ol'
                ];
                $this->db()->update('modules')
                ->values($fields)
                ->where(['key' => 'radiohoerercharts'])
                ->execute();
            case "1.2.0":
            // update zu 1.3.0
                /*
                In der Chart-Liste können Einträge ein-/ausgeblendet werden
                Wenn gewünscht können User/Gäste alle X Sekunden abstimmen
                User/Gäste können, wenn gewünscht, vorschläge machen für die Chart-Liste
                */
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts_uservotes` ADD COLUMN `last_activity` DATETIME NOT NULL  AFTER `session_id`;');
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts` ADD COLUMN `setfree` TINYINT(1) NOT NULL DEFAULT \'1\' AFTER `id`;');
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts` ADD COLUMN `datecreate` DATETIME NOT NULL AFTER `votes`;');
                $date = new \Ilch\Date();
                $datecreate = new \Ilch\Date($date->format("Y-m-d H:i:s", true));
                $this->db()->query('UPDATE `[prefix]_radio_hoerercharts` SET `datecreate` = "'.$datecreate.'"');
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts` ADD COLUMN `user_id` INT(11) NOT NULL AFTER `datecreate`;');
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_radio_hoerercharts_suggestion` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `interpret` VARCHAR(255) NOT NULL,
                  `songtitel` VARCHAR(255) NOT NULL,
                  `datecreate` DATETIME NOT NULL,
                  `user_id` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;');
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('radio_hoerercharts_all_sec_vote', '86400'); // 24h
                $databaseConfig->set('radio_hoerercharts_allow_suggestion', '1');
            case "1.3.0":
            // update zu 1.3.1
                /*
                fix indentation
                bugfix of redirect in Frontent
                */
            case "1.3.1":
            // update zu 1.3.2
                /*
                bugfix of DEFAULT value datecreate CURRENT_TIMESTAMP / now()
                */
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts_suggestion` CHANGE `datecreate` `datecreate` DATETIME NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts` CHANGE `datecreate` `datecreate` DATETIME NOT NULL;');
            case "1.3.2":
            // update zu 1.4.0
                /*
                shows the Voted entries
                shows the users / guests who submitted the entry
                small fixes
                */
            case "1.4.0":
            // update zu 1.4.1
                /*
                bugfix of SQL-Statement is_voted
                */
            case "1.4.1":
            // update zu 1.4.2
                /*
                bugfix of SQL-Statement is_voted
                Some Beauty fixes
                */
            case "1.4.2":
            // update zu 1.4.3
                /*
                Some Beauty fixes
                */
            case "1.4.3":
            // update zu 1.4.4
                /*
                Some Beauty code fixes
                Fix Date bug on create
                */
                $date = new \Ilch\Date();
                $datecreate = new \Ilch\Date($date->format("Y-m-d H:i:s", true));
                $this->db()->query('UPDATE `[prefix]_radio_hoerercharts` SET `datecreate` = "'.$datecreate.'" WHERE `datecreate` = "0000-00-00 00:00:00"');
            case "1.4.4":
            // update zu 1.5.0
                /*
                Admin View Sort #2 ( https://github.com/hhunderter/radiohoerercharts/issues/2 )
                */
            case "1.5.0":
            // update zu 1.5.1
                /*
                Change Font Awesome icons
                if voting period + additional time, there is no sorted issue #3 ( https://github.com/hhunderter/radiohoerercharts/issues/3 )
                */
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('radio_hoerercharts_Program_sec_duration', '7200'); //2h
            case "1.5.1":
            // update zu 1.5.2
                /*
                PSR2 Fix
                SameSite attribute
                Some Beauty code fixes
                Change Font Awesome icons
                ilch-Version: >= 2.1.41
                */
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('radio_hoerercharts_votetext_de', '[size=120][b]So funktioniert es:[/b][/size]
--user-- darf für seinen Lieblingstitel stimmen und an einem Bestimmten Tag (siehe: Sendeplan) wird es die Sendung "--name--" geben.
In dieser Sendung wird von unten (z.B. Platz 20) bis auf Platz 1 gespielt.
Danach werden die Stimmen von Ihnen wieder gelöscht und die Chartliste aktualisiert, d.h. es werden möglicherweise neue Interpreten hinzugefügt oder ältere Titel werden ausgeblendet usw.');
                $databaseConfig->set('radio_hoerercharts_votetext_en', '[b][size=120]So It Works:[/size][/b]
--user-- can vote for their favorite song and on a specific day (see: Broadcasting schedule) there will be the show "--name--".
This show plays from the bottom (e.g. 20th place) to the first place.
After that, the votes will be deleted again and updates the chart list, i.e. new artists may be added or older titles are hidden, etc.');
            case "1.5.2":
            // update zu 1.6.0
                /*
                add artworkUrl issue #1 (https://github.com/hhunderter/radiohoerercharts/issues/1)
                add Lists
                fix on Setfree selected on treat
                */
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts` ADD COLUMN `artworkUrl` VARCHAR(255) NOT NULL AFTER `user_id`;');
                $this->db()->query('CREATE TABLE IF NOT EXISTS `[prefix]_radio_hoerercharts_list` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `hid` int(11) NOT NULL,
                  `list` tinyint(1) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;');
                
                $hoererchartsMapper = new HoererChartsMapper();
                $hoererchartslistMapper = new HoererChartsListMapper();
                $chartsentries = $hoererchartsMapper->getEntriesby(['setfree' => 1]) ?? [];
                foreach ($chartsentries as $chartsentriesmodels) {
                    $hoererchartslistMapper->addEntryToList($chartsentriesmodels->getId(), 1);
                }
                
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('radio_hoerercharts_show_artwork', '0');
                $databaseConfig->set('radio_hoerercharts_active_list', '1');
            case "1.6.0":
            // update zu 1.6.1
                /*
                translations update
                */
                
        }
        return 'Update function executed.';
    }
}
