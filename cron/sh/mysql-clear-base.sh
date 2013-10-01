#!/bin/bash
php ./../cron.php dbcleaner clearDb
php ./../cron.php ipgeobaseupdate downloadDb
php ./../cron.php ipgeobaseupdate updateDb