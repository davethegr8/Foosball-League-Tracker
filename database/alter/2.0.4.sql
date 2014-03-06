ALTER TABLE `players` MODIFY `rank` int(11) unsigned DEFAULT 1000;

ALTER TABLE `players` ADD `foos_rank` int(11) unsigned DEFAULT 1000;
ALTER TABLE `players` ADD `foos_performance_rank` int(11) unsigned DEFAULT 1000;
ALTER TABLE `players` ADD `elo_rank` int(11) unsigned DEFAULT 1000;


ALTER TABLE `rank_track` MODIFY `rank` int(11) unsigned DEFAULT 1000;

ALTER TABLE `rank_track` ADD `foos_rank` int(11) unsigned DEFAULT NULL;
ALTER TABLE `rank_track` ADD `foos_performance_rank` int(11) unsigned DEFAULT NULL;
ALTER TABLE `rank_track` ADD `elo_rank` int(11) unsigned DEFAULT NULL;

ALTER TABLE `rank_track` MODIFY `foos_rank` int(11) unsigned DEFAULT 1000;
ALTER TABLE `rank_track` MODIFY `foos_performance_rank` int(11) unsigned DEFAULT 1000;
ALTER TABLE `rank_track` MODIFY `elo_rank` int(11) unsigned DEFAULT 1000;

