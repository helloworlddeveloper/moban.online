<?php

header('Content-Type: application/json');
 
echo json_encode(
    array(
        "Acura", "Audi", "BMW", "Buick", "Cadillac", "Chevrolet", "Dodge", "Chrysler", "Ford", "GMC", "Hyunday",
        "Infinity", "Jaguar", "Jeep", "Kia Motors", "Land Rover", "Lexus", "Lincoln", "Mazda", "Mercedes-benz",
        "Mitsubishi", "Nissan", "Pontiac", "Porsche", "Saab", "Saturn", "Scion", "Subaru", "Suzuki", "Toyota",
        "Honda", "Hummer", "Mercury", "Mini", "Volkswagen", "Volvo"
    )
);