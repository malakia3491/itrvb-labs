<?php

namespace Lab3\User;

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
}