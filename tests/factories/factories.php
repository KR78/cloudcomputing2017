<?php

$factory('App\User', [
  'name' => $faker->firstName($gender = null|'male'|'female'),
  'username' => $faker->unique()->name,
  'dob' => $faker->date($format = 'Y-m-d', $max = 'now'),
  'gender' => $faker->randomElement($array = array ('Male','Female')),
  'phone_no' => $faker->e164PhoneNumber,
  'email' => $faker->unique()->email,
  'website' => $faker->unique()->domainName,
  'displayName' => $faker->unique()->username,
  'location' => $faker->country,
  'active' => $faker->randomElement($array = array (0,1)),
  'avatar' => $faker->imageUrl($width = 640, $height = 480, 'people'),
  'facebook' => $faker->randomNumber($nbDigits = 9),
  'instagram' => $faker->randomNumber($nbDigits = 9),
  'twitter' => $faker->randomNumber($nbDigits = 9),
  'ip_address' => $faker->ipv4,
  'last_activity' => $faker->dateTimeThisYear($max = 'now'),
  'google2fa_secret' => $faker->randomNumber($nbDigits = 6),
  'password' => Hash::make('secret')
]);
/* --- Categories
*/

$factory('App\File', [
  'file_name' => $faker->word,
  'file_type' => $faker->imageUrl($width = 800, $height = 480, 'people'),
  'file_path' => $faker->paragraphs($nbSentences = 10, $variableNbSentences = true),
  'mime_type' => $faker->randomElement($array = array (1,2,3)),
  'location' => $faker->address,
  'end_date' => $faker->dateTimeThisYear($max = 'now'),
  'start_date' => $faker->dateTimeThisYear($max = 'now')
]);
