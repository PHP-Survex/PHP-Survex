<?php

namespace PHPSurvex\PHPSurvex\Converters;

use PHPSurvex\PHPSurvex\Commands\Team;
use PHPSurvex\PHPSurvex\Parser\Line;
use PHPSurvex\PHPSurvex\Support\Collection;

class TeamConverter
{
    public function convert(Line $line)
    {
        $values = new Collection($line->getData()->getValues());

        foreach ($this->patterns() as $pattern) {
            preg_match($pattern, $line->getData()->getContent(), $matches);

            if (!isset($matches['name'])) {
                continue;
            }

            $team = new Team($matches['name']);

            if (isset($matches['roles'])) {
                $roles = preg_replace('/\s+/', "\t", $matches['roles']);
                $roles = explode("\t", $roles);

                $team->addRoles($roles);
            }

            return $team;
        }

        throw new \Exception('invalid team member');
    }

    public function patterns()
    {
        return [
            '/^"(?P<name>.+?)"(?:\s+(?P<roles>.+?))?$/',
            '/^(?P<name>.+?)(?:\s+(?P<roles>.+?))?$/',
        ];
    }
}
