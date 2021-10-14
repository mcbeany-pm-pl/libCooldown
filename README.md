# libCooldown
 Cooldown Library (virion) for PocketMine-MP

## Example

```php

// libCooldown::newTimer(int $cooldown_time, bool $first_ignore = true): libCooldown;
$timer = libCooldown::newTimer(30); // 30 secs
// libCooldown->ignorePlayer(Player $player);
$timer->ignorePlayer($ownerBecauseHeIsPro);
// libCooldown->checkCooldown(Player $player, Closure $during_cooldown, Closure $no_longer);
$timer->checkCooldown($player, function(Player $p) {
    $p->sendMessage("Not on cooldown. Great!");
}, function(Player $p, int $r) {
    $p->sendMessage($r . " seconds left...");
});

```
