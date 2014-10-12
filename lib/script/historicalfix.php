<?php

$require_login = false;
require '../../knoopvszombies.ini.php';

require '../../www/module/includes.php';

require '../../www/module/general.php';
  
$all_users = $GLOBALS['User']->GetAccounts('all', 1);
$sql = 'SELECT gid, start_time, end_time from game where archive = 1';
$games = $GLOBALS['Db']->GetRecords($sql);
$GLOBALS['User']->ClearAllUserCache();
foreach ($all_users as $user)
{
  $kills = 0;
  $human_time = 0;

  foreach ($games as $game){
    $row = $GLOBALS['User']->GetUserFromGame($user['uid'], $game['gid']);
    $GLOBALS['User']->ClearAllUserCache();
    if ($row) {
      $kills += $row['zombie_kills'];

      $lived_until = ($row['zombied_time'] >= $game['start_time'] ? $row['zombied_time'] : $game['end_time']);
      $human_time += $lived_until - $game['start_time'];
    }
  }
  $sql = "UPDATE historical SET zombie_kills = $kills, time_alive = $human_time WHERE uid = '".$user['uid']."'";
  $GLOBALS['Db']->Execute($sql); 
}

?>

All done.
