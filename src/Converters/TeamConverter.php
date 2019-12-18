<?php

namespace Dartui\Survex\Converters;

use Dartui\Survex\Commands\Team;
use Dartui\Survex\Parser\Line;
use Dartui\Survex\Support\Collection;

class TeamConverter
{
    public function convert(Line $line)
    {
        $values = new Collection($line->getData()->getValues());

        $patterns = [
            '/^"(?P<name>.+?)"(?:\s+(?P<roles>.+?))?$/',
            '/^(?P<name>.+?)(?:\s+(?P<roles>.+?))?$/',
        ];

        foreach ($patterns as $pattern) {
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
}
