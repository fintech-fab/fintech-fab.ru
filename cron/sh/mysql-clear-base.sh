#!/bin/bash
php ./../cron.php clientdatasender sendClientData
php ./../cron.php dbcleaner clearDb
php ./../cron.php ipgeobaseupdate downloadDb
php ./../cron.php ipgeobaseupdate updateDb
