<?php

use App\Infrastructure\Database\ConnectionCreator;

require_once 'vendor/autoload.php';

$connection = (new ConnectionCreator())->create();

$createTablesSql = '
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY,
        name TEXT
    );

    CREATE TABLE IF NOT EXISTS staff_members (
        id INTEGER PRIMARY KEY,
        name TEXT,
        is_fired DEFAULT 0
    );
    
    CREATE TABLE IF NOT EXISTS services (
        id INTEGER PRIMARY KEY,
        name TEXT,
        price_in_cents INTEGER,
        duration_minutes INTEGER,
        is_active INTEGER DEFAULT 1,
        member_id INTERGER,
        FOREIGN KEY(member_id) REFERENCES staff_members(id)
    );

    CREATE TABLE IF NOT EXISTS schedules (
        id INTEGER PRIMARY KEY,
        date TEXT,
        is_taken INTEGER DEFAULT 0,
        service_id INTEGER,
        FOREIGN KEY(service_id) REFERENCES services(id)
    );

    CREATE TABLE IF NOT EXISTS customers (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT,
        document TEXT,
        is_banned INTEGER DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY,
        customer_id INTEGER,
        schedule_id INTEGER,
        FOREIGN KEY(customer_id) REFERENCES customers(id),
        FOREIGN KEY(schedule_id) REFERENCES schedule(id)
    );
';

var_dump($connection->exec($createTablesSql));