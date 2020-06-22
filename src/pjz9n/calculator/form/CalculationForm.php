<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of Calculator.
 *
 * Calculator is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Calculator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Calculator. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace pjz9n\calculator\form;

use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class CalculationForm implements Form
{
    /** @var Plugin */
    private $plugin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }
        $a = $data[0];
        $b = $data[2];
        if (!is_numeric($a) ||
            !is_numeric($b)) {
            $player->sendMessage(TextFormat::RED . "入力された値が不正です。");
            return;
        }
        $a = (int)$a;
        $b = (int)$b;
        if ($a < PHP_INT_MIN ||
            $a > PHP_INT_MAX ||
            $b < PHP_INT_MIN ||
            $b > PHP_INT_MAX) {
            $player->sendMessage(TextFormat::RED . "入力された値の範囲が不正です。");
            return;
        }
        $player->sendMessage(
            TextFormat::AQUA . (string)$a .
            TextFormat::YELLOW . " + " .
            TextFormat::AQUA . (string)$b .
            TextFormat::YELLOW . " = " .
            TextFormat::GREEN . ($a + $b)
        );
    }

    public function jsonSerialize(): array
    {
        return [
            "type" => "custom_form",
            "title" => (string)$this->plugin->getConfig()->get("title", "Unknown"),
            "content" => [
                [
                    "type" => "input",
                    "text" => "A",
                ],
                [
                    "type" => "label",
                    "text" => "+",
                ],
                [
                    "type" => "input",
                    "text" => "B",
                ],
            ],
        ];
    }
}
