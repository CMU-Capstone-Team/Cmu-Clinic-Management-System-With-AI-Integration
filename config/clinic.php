<?php

return [
    'admin' => [
        'employee_id' => env(
            'CLINIC_ADMIN_EMPLOYEE_ID',
            'CMU-ADMIN-001'
        ),

        'name' => env(
            'CLINIC_ADMIN_NAME',
            'Clinic Administrator'
        ),

        'email' => env(
            'CLINIC_ADMIN_EMAIL',
            'admin@clinic.test'
        ),

        'password' => env('CLINIC_ADMIN_PASSWORD'),
    ],
];