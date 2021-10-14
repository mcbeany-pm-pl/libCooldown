<?php

declare(strict_types=1);

namespace Mcbeany\libCooldown;

use Closure;
use pocketmine\Player;

class libCooldown
{

    // Blame my stupid english skills

    /** @var int */
    private int $cooldown_time;
    /** @var bool */
    private bool $first_ignore;
    /** @var int[] */
    private array $players = [];
    /** @var string[] */
    private array $ignores = [];

    /**
     * @param int $cooldown_time
     * @param bool $first_ignore
     * @return libCooldown
     */
    public static function newTimer(int $cooldown_time, bool $first_ignore = true): libCooldown
    {
        $timer = new libCooldown();
        $timer->cooldown_time = $cooldown_time;
        $timer->first_ignore = $first_ignore;
        return $timer;
    }

    /**
     * @param Player $player
     */
    public function ignorePlayer(Player $player)
    {
        $this->ignores[] = $player->getRawUniqueId();
    }

    /**
     * @param Player $player
     */
    public function unignorePlayer(Player $player)
    {
        unset($this->ignores[array_search($player->getRawUniqueId(), $this->ignores)]);
    }


    /**
     *
     * @param Player $player
     * @param Closure $during_cooldown
     * @param Closure $no_longer
     */
    public function checkCooldown(Player $player, Closure $during_cooldown, Closure $no_longer)
    {
        $id = $player->getRawUniqueId();
        if (isset($this->ignores[$id])) {
            $during_cooldown($player);
            return;
        }
        if (!isset($this->players[$id])) {
            $this->players[$id] = time() + ($this->first_ignore ? 0 : $this->cooldown_time);
        }
        $time = $this->players[$id];
        if ($time <= time()) {
            $during_cooldown($player);
            $this->players[$id] = time() + $this->cooldown_time;
            return;
        }
        $remaining = $time - time();
        $no_longer($player, $remaining);
    }
}