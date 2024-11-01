<?php

return [
    'required' => 'ველი :attribute აუცილებელია.',
    'email' => 'გთხოვთ, შეიყვანოთ სწორი ელფოსტა.',
    'password' => 'პაროლი არასწორია.',
    'confirmed' => 'პაროლის დადასტურება არ ემთხვევა.',
    'min' => [
        'string' => ':attribute უნდა იყოს მინიმუმ :min სიმბოლო.',
    ],
    'max' => [
        'string' => ':attribute არ უნდა შეიცავდეს :max სიმბოლოზე მეტს.',
    ],
    'unique' => [
        'name' => 'ეს ავტორი ან ელფოსტა უკვე დარეგისტრირებულია', // Custom message for the unique name rule on authors
        'email' => 'ელფოსტა უკვე გამოყენებულია', // Custom message for unique email
    ],
        'exists' => 'არჩეული :attribute არასწორია.',
    'current_password' => 'პაროლი არასწორია.',
    'string' => ':attribute უნდა იყოს ტექსტი.',
    'same' => ':attribute და :other უნდა ემთხვეოდეს.',
    'min' => [
        'string' => 'პაროლი უნდა შეიცავდეს მინიმუმ :min სიმბოლოს.',
    ],
];

