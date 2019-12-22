<?php

namespace PHPSurvex\PHPSurvex\Commands;

class Team
{
    protected $name;

    protected $roles;

    final public function __construct($name, array $roles = [])
    {
        $this->roles = new TeamRoleCollection();

        $this->setName($name);
        $this->addRoles($roles);
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function addRoles(array $roles)
    {
        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    public function addRole($role)
    {
        $this->roles->append($role);

        return $this;
    }
}
