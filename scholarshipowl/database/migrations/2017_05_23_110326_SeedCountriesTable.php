<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Afghanistan', 'AF');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Åland Islands', 'AX');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Albania', 'AL');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Algeria', 'DZ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'American Samoa', 'AS');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Andorra', 'AD');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Angola', 'AO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Anguilla', 'AI');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Antarctica', 'AQ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Antigua and Barbuda', 'AG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Argentina', 'AR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Armenia', 'AM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Aruba', 'AW');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Australia', 'AU');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Austria', 'AT');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Azerbaijan', 'AZ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Bahamas', 'BS');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Bahrain', 'BH');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Bangladesh', 'BD');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Barbados', 'BB');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Belarus', 'BY');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Belgium', 'BE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Belize', 'BZ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Benin', 'BJ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Bermuda', 'BM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Bhutan', 'BT');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Bolivia, Plurinational State of', 'BO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Bonaire, Sint Eustatius and Saba', 'BQ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Bosnia and Herzegovina', 'BA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Botswana', 'BW');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Bouvet Island', 'BV');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Brazil', 'BR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'British Indian Ocean Territory', 'IO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Brunei Darussalam', 'BN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Bulgaria', 'BG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Burkina Faso', 'BF');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Burundi', 'BI');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Cambodia', 'KH');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Cameroon', 'CM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Canada', 'CA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Cape Verde', 'CV');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Cayman Islands', 'KY');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Central African Republic', 'CF');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Chad', 'TD');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Chile', 'CL');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'China', 'CN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Christmas Island', 'CX');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Cocos (Keeling) Islands', 'CC');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Colombia', 'CO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Comoros', 'KM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Congo', 'CG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Congo, the Democratic Republic of the', 'CD');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Cook Islands', 'CK');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Costa Rica', 'CR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Côte d\'Ivoire', 'CI');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Croatia', 'HR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Cuba', 'CU');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Curaçao', 'CW');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Cyprus', 'CY');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Czech Republic', 'CZ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Denmark', 'DK');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Djibouti', 'DJ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Dominica', 'DM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Dominican Republic', 'DO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Ecuador', 'EC');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Egypt', 'EG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'El Salvador', 'SV');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Equatorial Guinea', 'GQ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Eritrea', 'ER');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Estonia', 'EE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Ethiopia', 'ET');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Falkland Islands (Malvinas)', 'FK');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Faroe Islands', 'FO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Fiji', 'FJ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Finland', 'FI');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'France', 'FR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'French Guiana', 'GF');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'French Polynesia', 'PF');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'French Southern Territories', 'TF');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Gabon', 'GA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Gambia', 'GM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Georgia', 'GE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Germany', 'DE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Ghana', 'GH');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Gibraltar', 'GI');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Greece', 'GR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Greenland', 'GL');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Grenada', 'GD');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Guadeloupe', 'GP');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Guam', 'GU');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Guatemala', 'GT');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Guernsey', 'GG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Guinea', 'GN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Guinea-Bissau', 'GW');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Guyana', 'GY');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Haiti', 'HT');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Heard Island and McDonald Islands', 'HM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Holy See (Vatican City State)', 'VA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Honduras', 'HN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Hong Kong', 'HK');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Hungary', 'HU');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Iceland', 'IS');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'India', 'IN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Indonesia', 'ID');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Iran, Islamic Republic of', 'IR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Iraq', 'IQ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Ireland', 'IE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Isle of Man', 'IM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Israel', 'IL');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Italy', 'IT');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Jamaica', 'JM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Japan', 'JP');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Jersey', 'JE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Jordan', 'JO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Kazakhstan', 'KZ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Kenya', 'KE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Kiribati', 'KI');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Korea, Democratic People\'s Republic of', 'KP');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Korea, Republic of', 'KR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Kuwait', 'KW');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Kyrgyzstan', 'KG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Lao People\'s Democratic Republic', 'LA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Latvia', 'LV');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Lebanon', 'LB');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Lesotho', 'LS');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Liberia', 'LR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Libya', 'LY');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Liechtenstein', 'LI');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Lithuania', 'LT');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Luxembourg', 'LU');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Macao', 'MO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Macedonia, the Former Yugoslav Republic of', 'MK');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Madagascar', 'MG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Malawi', 'MW');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Malaysia', 'MY');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Maldives', 'MV');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Mali', 'ML');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Malta', 'MT');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Marshall Islands', 'MH');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Martinique', 'MQ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Mauritania', 'MR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Mauritius', 'MU');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Mayotte', 'YT');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Mexico', 'MX');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Micronesia, Federated States of', 'FM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Moldova, Republic of', 'MD');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Monaco', 'MC');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Mongolia', 'MN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Montenegro', 'ME');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Montserrat', 'MS');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Morocco', 'MA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Mozambique', 'MZ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Myanmar', 'MM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Namibia', 'NA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Nauru', 'NR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Nepal', 'NP');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Netherlands', 'NL');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'New Caledonia', 'NC');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'New Zealand', 'NZ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Nicaragua', 'NI');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Niger', 'NE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Nigeria', 'NG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Niue', 'NU');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Norfolk Island', 'NF');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Northern Mariana Islands', 'MP');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Norway', 'NO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Oman', 'OM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Pakistan', 'PK');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Palau', 'PW');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Palestine, State of', 'PS');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Panama', 'PA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Papua New Guinea', 'PG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Paraguay', 'PY');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Peru', 'PE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Philippines', 'PH');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Pitcairn', 'PN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Poland', 'PL');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Portugal', 'PT');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Puerto Rico', 'PR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Qatar', 'QA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Réunion', 'RE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Romania', 'RO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Russian Federation', 'RU');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Rwanda', 'RW');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Saint Barthélemy', 'BL');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Saint Helena, Ascension and Tristan da Cunha', 'SH');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Saint Kitts and Nevis', 'KN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Saint Lucia', 'LC');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Saint Martin (French part)', 'MF');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Saint Pierre and Miquelon', 'PM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Saint Vincent and the Grenadines', 'VC');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Samoa', 'WS');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'San Marino', 'SM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Sao Tome and Principe', 'ST');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Saudi Arabia', 'SA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Senegal', 'SN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Serbia', 'RS');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Seychelles', 'SC');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Sierra Leone', 'SL');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Singapore', 'SG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Sint Maarten (Dutch part)', 'SX');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Slovakia', 'SK');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Slovenia', 'SI');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Solomon Islands', 'SB');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Somalia', 'SO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'South Africa', 'ZA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'South Georgia and the South Sandwich Islands', 'GS');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'South Sudan', 'SS');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Spain', 'ES');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Sri Lanka', 'LK');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Sudan', 'SD');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Suriname', 'SR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Svalbard and Jan Mayen', 'SJ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Swaziland', 'SZ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Sweden', 'SE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Switzerland', 'CH');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Syrian Arab Republic', 'SY');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Taiwan, Province of China', 'TW');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Tajikistan', 'TJ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Tanzania, United Republic of', 'TZ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Thailand', 'TH');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Timor-Leste', 'TL');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Togo', 'TG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Tokelau', 'TK');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Tonga', 'TO');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Trinidad and Tobago', 'TT');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Tunisia', 'TN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Turkey', 'TR');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Turkmenistan', 'TM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Turks and Caicos Islands', 'TC');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Tuvalu', 'TV');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Uganda', 'UG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Ukraine', 'UA');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'United Arab Emirates', 'AE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'United Kingdom', 'GB');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'United States Minor Outlying Islands', 'UM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Uruguay', 'UY');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Uzbekistan', 'UZ');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Vanuatu', 'VU');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Venezuela, Bolivarian Republic of', 'VE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Viet Nam', 'VN');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Virgin Islands, British', 'VG');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Virgin Islands, U.S.', 'VI');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Wallis and Futuna', 'WF');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Western Sahara', 'EH');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Yemen', 'YE');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Zambia', 'ZM');");
        DB::statement("INSERT INTO `country` (`country_id`, `name`, `abbreviation`) VALUES (NULL, 'Zimbabwe', 'ZW');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DELETE FROM country WHERE name != 'USA';");
    }
}
