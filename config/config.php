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
        'version' => '1.9.0',
        'icon_small' => 'fa-solid fa-list-ol',
        'author' => 'Reilard, Dennis alias hhunderter',
        'link' => 'https://github.com/hhunderter/radiohoerercharts',
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
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.3',
    ];

    public function install()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('radio_hoerercharts_Guest_Allow', '0')
            ->set('radio_hoerercharts_Start_Datetime', '')
            ->set('radio_hoerercharts_End_Datetime', '')
            ->set('radio_hoerercharts_Program_Name', 'Hörercharts')
            ->set('radio_hoerercharts_showstars', '1')
            ->set('radio_hoerercharts_Star1', '1')
            ->set('radio_hoerercharts_Star2', '2')
            ->set('radio_hoerercharts_Star3', '3')
            ->set('radio_hoerercharts_Star4', '4')
            ->set('radio_hoerercharts_Star5', '5')
            ->set('radio_hoerercharts_all_sec_vote', '86400') // 24h
            ->set('radio_hoerercharts_allow_suggestion', '1')
            ->set('radio_hoerercharts_Program_sec_duration', '7200') // 2h
            ->set('radio_hoerercharts_votetext_de', '<p><span style="font-size:120%;"><strong>So funktioniert es:</strong></span> --user-- darf f&uuml;r seinen Lieblingstitel stimmen und an einem bestimmten Tag (siehe: Sendeplan) wird es die Sendung &quot;--name--&quot; geben. In dieser Sendung wird von unten (z.B. Platz 20) bis auf Platz 1 gespielt. Danach werden die Stimmen von Ihnen wieder gel&ouml;scht und die Chartliste aktualisiert, d.h. es werden m&ouml;glicherweise neue Interpreten hinzugef&uuml;gt oder &auml;ltere Titel werden ausgeblendet usw.</p>')
            ->set('radio_hoerercharts_votetext_en', '<p><span style="font-size:120%;"><strong>So It Works:</strong></span>--user-- can vote for their favorite song and on a specific day (see: Broadcasting schedule) there will be the show &quot;--name--&quot;. This show plays from the bottom (e.g. 20th place) to the first place. After that, the votes will be deleted again and updates the chart list, i.e. new artists may be added or older titles are hidden, etc.</p>')
            ->set('radio_hoerercharts_show_artwork', '0')
            ->set('radio_hoerercharts_active_list', '1');

        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->delete('radio_hoerercharts_Guest_Allow')
            ->delete('radio_hoerercharts_Start_Datetime')
            ->delete('radio_hoerercharts_End_Datetime')
            ->delete('radio_hoerercharts_Program_Name')
            ->delete('radio_hoerercharts_showstars')
            ->delete('radio_hoerercharts_Star1')
            ->delete('radio_hoerercharts_Star2')
            ->delete('radio_hoerercharts_Star3')
            ->delete('radio_hoerercharts_Star4')
            ->delete('radio_hoerercharts_Star5')
            ->delete('radio_hoerercharts_all_sec_vote')
            ->delete('radio_hoerercharts_allow_suggestion')
            ->delete('radio_hoerercharts_Program_sec_duration')
            ->delete('radio_hoerercharts_votetext_de')
            ->delete('radio_hoerercharts_votetext_en')
            ->delete('radio_hoerercharts_show_artwork')
            ->delete('radio_hoerercharts_active_list');


        $this->db()->drop('radio_hoerercharts', true);
        $this->db()->drop('radio_hoerercharts_uservotes', true);
        $this->db()->drop('radio_hoerercharts_suggestion', true);
        $this->db()->drop('radio_hoerercharts_list', true);
    }

    public function getInstallSql(): string
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
                  `ip_address` VARCHAR(255) NOT NULL,
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

    public function getUpdate(string $installedVersion): string
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
                // no break
            case "1.0.1":
            // update zu 1.1.0
                /*
                Bugfixes
                Gäste können, wenn gewünscht auch abstimmen
                -session_id eingeführt
                */
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts_uservotes` ADD COLUMN `session_id` VARCHAR(255) NOT NULL DEFAULT \'\' AFTER `user_id`;');
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('radio_hoerercharts_Guest_Allow', '0');
                // no break
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
                    'author' => 'Reilard, Dennis alias hhunderter',
                    'icon_small' => 'fa-solid fa-list-ol'
                ];
                $this->db()->update('modules')
                    ->values($fields)
                    ->where(['key' => 'radiohoerercharts'])
                    ->execute();
                // no break
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
                $this->db()->query('UPDATE `[prefix]_radio_hoerercharts` SET `datecreate` = "' . $datecreate . '"');
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
                // no break
            case "1.3.0":
            // update zu 1.3.1
                /*
                fix indentation
                bugfix of redirect in Frontent
                */
                // no break
            case "1.3.1":
            // update zu 1.3.2
                /*
                bugfix of DEFAULT value datecreate CURRENT_TIMESTAMP / now()
                */
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts_suggestion` CHANGE `datecreate` `datecreate` DATETIME NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts` CHANGE `datecreate` `datecreate` DATETIME NOT NULL;');
                // no break
            case "1.3.2":
            // update zu 1.4.0
                /*
                shows the Voted entries
                shows the users / guests who submitted the entry
                small fixes
                */
                // no break
            case "1.4.0":
            // update zu 1.4.1
                /*
                bugfix of SQL-Statement is_voted
                */
            // no break
            case "1.4.1":
            // update zu 1.4.2
                /*
                bugfix of SQL-Statement is_voted
                Some Beauty fixes
                */
                // no break
            case "1.4.2":
            // update zu 1.4.3
                /*
                Some Beauty fixes
                */
                // no break
            case "1.4.3":
            // update zu 1.4.4
                /*
                Some Beauty code fixes
                Fix Date bug on create
                */
                $date = new \Ilch\Date();
                $datecreate = new \Ilch\Date($date->format("Y-m-d H:i:s", true));
                $this->db()->query('UPDATE `[prefix]_radio_hoerercharts` SET `datecreate` = "' . $datecreate . '" WHERE `datecreate` = "0000-00-00 00:00:00"');
                // no break
            case "1.4.4":
            // update zu 1.5.0
                /*
                Admin View Sort #2 ( https://github.com/hhunderter/radiohoerercharts/issues/2 )
                */
                // no break
            case "1.5.0":
            // update zu 1.5.1
                /*
                Change Font Awesome icons
                if voting period + additional time, there is no sorted issue #3 ( https://github.com/hhunderter/radiohoerercharts/issues/3 )
                */
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('radio_hoerercharts_Program_sec_duration', '7200'); //2h
                // no break
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
                $databaseConfig->set('radio_hoerercharts_votetext_de', '<p><span style="font-size:120%;"><strong>So funktioniert es:</strong></span> --user-- darf f&uuml;r seinen Lieblingstitel stimmen und an einem bestimmten Tag (siehe: Sendeplan) wird es die Sendung &quot;--name--&quot; geben. In dieser Sendung wird von unten (z.B. Platz 20) bis auf Platz 1 gespielt. Danach werden die Stimmen von Ihnen wieder gel&ouml;scht und die Chartliste aktualisiert, d.h. es werden m&ouml;glicherweise neue Interpreten hinzugef&uuml;gt oder &auml;ltere Titel werden ausgeblendet usw.</p>');
                $databaseConfig->set('radio_hoerercharts_votetext_en', '<p><span style="font-size:120%;"><strong>So It Works:</strong></span>--user-- can vote for their favorite song and on a specific day (see: Broadcasting schedule) there will be the show &quot;--name--&quot;. This show plays from the bottom (e.g. 20th place) to the first place. After that, the votes will be deleted again and updates the chart list, i.e. new artists may be added or older titles are hidden, etc.</p>');
                // no break
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
                // no break
            case "1.6.0":
            // update zu 1.6.1
                /*
                translations update
                */

                // no break
            case "1.6.1":
            // update zu 1.7.0
                /*
                add ip-sperre  issue #4 (https://github.com/hhunderter/radiohoerercharts/issues/4)
                add option to show "Registered by"
                fix show artwork on non Voted
                fix reset by only change artwork URL
                */
                $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts_uservotes` ADD `ip_address` VARCHAR(255) NOT NULL AFTER `session_id`;');
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $databaseConfig->set('radio_hoerercharts_show_registered_by', '1');
                $fields = [
                    'link' => 'https://github.com/hhunderter/radiohoerercharts'
                ];
                $this->db()->update('modules')
                ->values($fields)
                ->where(['key' => 'radiohoerercharts'])
                ->execute();
            // no break
            case "1.7.0":
                // update zu 1.8.0
                /*
                 * Ilch-Core und PHP-Version Anpassung
                 * FontAwesome 6
                 * Code Verbesserungen
                 * Captcha Anpassung
                */
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = '" . $this->config['icon_small'] . "' WHERE `key` = '" . $this->config['key'] . "';");
                // no break
            case "1.8.0":
                // update zu 1.8.1
                /*
                 * Fehler mit PHP 7.3 Kompatibilität behoben
                 * BBCode entfernt
                 * Fehlende Spalte bei neu Installation
                */
                if (!$this->db()->ifColumnExists('radio_hoerercharts_uservotes', 'ip_address')) {
                    $this->db()->query('ALTER TABLE `[prefix]_radio_hoerercharts_uservotes` ADD `ip_address` VARCHAR(255) NOT NULL AFTER `session_id`;');
                }
                // no break
            case "1.8.1":
                // update zu 1.8.2
                /*
                 * Captcha Fehler behoben
                 * Neue einträge (Backend) automatisch Freigeben
                */
                // no break
            case "1.8.2":
                // update zu 1.8.3
                /*
                 * IP Adresse wurde nicht gespeichert
                */
                // no break
            case "1.8.3":
                // update zu 1.9.0
                /*
                 * Anpassungen für Bootstrap 5
                */
                // no break
        }
        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}
