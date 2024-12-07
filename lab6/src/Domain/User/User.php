<?php

namespace Lab3\Domain\User;

class User 
{
    public string $uuid;
    public string $username;
    public string $firstName;
    public string $lastName;

    public function __construct(string $uuid, string $username, string $firstName, string $lastName) {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getUuid(): string 
    {
        return $this->uuid;
    }

    public function getUsername(): string 
    {
        return $this->username;
    }

    public function getFirstName(): string 
    {
        return $this->firstName;
    }

    public function getLastName(): string 
    {
        return $this->lastName;
    }
}