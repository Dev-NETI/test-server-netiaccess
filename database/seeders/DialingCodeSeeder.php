<?php

namespace Database\Seeders;

use App\Models\DialingCode;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DialingCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dialingCodes = [
            "Afghanistan" => ["93", "AF"],
            "Albania" => ["355", "AL"],
            "Algeria" => ["213", "DZ"],
            "Andorra" => ["376", "AD"],
            "Angola" => ["244", "AO"],
            "Antigua and Barbuda" => ["1268", "AG"],
            "Argentina" => ["54", "AR"],
            "Armenia" => ["374", "AM"],
            "Australia" => ["61", "AU"],
            "Austria" => ["43", "AT"],
            "Azerbaijan" => ["994", "AZ"],
            "Bahamas" => ["1242", "BS"],
            "Bahrain" => ["973", "BH"],
            "Bangladesh" => ["880", "BD"],
            "Barbados" => ["1246", "BB"],
            "Belarus" => ["375", "BY"],
            "Belgium" => ["32", "BE"],
            "Belize" => ["501", "BZ"],
            "Benin" => ["229", "BJ"],
            "Bhutan" => ["975", "BT"],
            "Bolivia" => ["591", "BO"],
            "Bosnia and Herzegovina" => ["387", "BA"],
            "Botswana" => ["267", "BW"],
            "Brazil" => ["55", "BR"],
            "Brunei" => ["673", "BN"],
            "Bulgaria" => ["359", "BG"],
            "Burkina Faso" => ["226", "BF"],
            "Burundi" => ["257", "BI"],
            "Cabo Verde" => ["238", "CV"],
            "Cambodia" => ["855", "KH"],
            "Cameroon" => ["237", "CM"],
            "Canada" => ["1", "CA"],
            "Central African Republic" => ["236", "CF"],
            "Chad" => ["235", "TD"],
            "Chile" => ["56", "CL"],
            "China" => ["86", "CN"],
            "Colombia" => ["57", "CO"],
            "Comoros" => ["269", "KM"],
            "Congo" => ["242", "CG"],
            "Costa Rica" => ["506", "CR"],
            "Croatia" => ["385", "HR"],
            "Cuba" => ["53", "CU"],
            "Cyprus" => ["357", "CY"],
            "Czech Republic" => ["420", "CZ"],
            "Denmark" => ["45", "DK"],
            "Djibouti" => ["253", "DJ"],
            "Dominica" => ["1767", "DM"],
            "Dominican Republic" => ["1809", "DO"],
            "East Timor (Timor-Leste)" => ["670", "TL"],
            "Ecuador" => ["593", "EC"],
            "Egypt" => ["20", "EG"],
            "El Salvador" => ["503", "SV"],
            "Equatorial Guinea" => ["240", "GQ"],
            "Eritrea" => ["291", "ER"],
            "Estonia" => ["372", "EE"],
            "Eswatini" => ["268", "SZ"],
            "Ethiopia" => ["251", "ET"],
            "Fiji" => ["679", "FJ"],
            "Finland" => ["358", "FI"],
            "France" => ["33", "FR"],
            "Gabon" => ["241", "GA"],
            "Gambia" => ["220", "GM"],
            "Georgia" => ["995", "GE"],
            "Germany" => ["49", "DE"],
            "Ghana" => ["233", "GH"],
            "Greece" => ["30", "GR"],
            "Grenada" => ["1473", "GD"],
            "Guatemala" => ["502", "GT"],
            "Guinea" => ["224", "GN"],
            "Guinea-Bissau" => ["245", "GW"],
            "Guyana" => ["592", "GY"],
            "Haiti" => ["509", "HT"],
            "Honduras" => ["504", "HN"],
            "Hungary" => ["36", "HU"],
            "Iceland" => ["354", "IS"],
            "India" => ["91", "IN"],
            "Indonesia" => ["62", "ID"],
            "Iran" => ["98", "IR"],
            "Iraq" => ["964", "IQ"],
            "Ireland" => ["353", "IE"],
            "Israel" => ["972", "IL"],
            "Italy" => ["39", "IT"],
            "Ivory Coast" => ["225", "CI"],
            "Jamaica" => ["1876", "JM"],
            "Japan" => ["81", "JP"],
            "Jordan" => ["962", "JO"],
            "Kazakhstan" => ["7", "KZ"],
            "Kenya" => ["254", "KE"],
            "Kiribati" => ["686", "KI"],
            "Kosovo" => ["383", "XK"],
            "Kuwait" => ["965", "KW"],
            "Kyrgyzstan" => ["996", "KG"],
            "Laos" => ["856", "LA"],
            "Latvia" => ["371", "LV"],
            "Lebanon" => ["961", "LB"],
            "Lesotho" => ["266", "LS"],
            "Liberia" => ["231", "LR"],
            "Libya" => ["218", "LY"],
            "Liechtenstein" => ["423", "LI"],
            "Lithuania" => ["370", "LT"],
            "Luxembourg" => ["352", "LU"],
            "Madagascar" => ["261", "MG"],
            "Malawi" => ["265", "MW"],
            "Malaysia" => ["60", "MY"],
            "Maldives" => ["960", "MV"],
            "Mali" => ["223", "ML"],
            "Malta" => ["356", "MT"],
            "Marshall Islands" => ["692", "MH"],
            "Mauritania" => ["222", "MR"],
            "Mauritius" => ["230", "MU"],
            "Mexico" => ["52", "MX"],
            "Micronesia" => ["691", "FM"],
            "Moldova" => ["373", "MD"],
            "Monaco" => ["377", "MC"],
            "Mongolia" => ["976", "MN"],
            "Montenegro" => ["382", "ME"],
            "Morocco" => ["212", "MA"],
            "Mozambique" => ["258", "MZ"],
            "Myanmar (Burma)" => ["95", "MM"],
            "Namibia" => ["264", "NA"],
            "Nauru" => ["674", "NR"],
            "Nepal" => ["977", "NP"],
            "Netherlands" => ["31", "NL"],
            "New Zealand" => ["64", "NZ"],
            "Nicaragua" => ["505", "NI"],
            "Niger" => ["227", "NE"],
            "Nigeria" => ["234", "NG"],
            "North Korea" => ["850", "KP"],
            "North Macedonia (Macedonia)" => ["389", "MK"],
            "Norway" => ["47", "NO"],
            "Oman" => ["968", "OM"],
            "Pakistan" => ["92", "PK"],
            "Palau" => ["680", "PW"],
            "Panama" => ["507", "PA"],
            "Papua New Guinea" => ["675", "PG"],
            "Paraguay" => ["595", "PY"],
            "Peru" => ["51", "PE"],
            "Philippines" => ["63", "PH"],
            "Poland" => ["48", "PL"],
            "Portugal" => ["351", "PT"],
            "Qatar" => ["974", "QA"],
            "Romania" => ["40", "RO"],
            "Russia" => ["7", "RU"],
            "Rwanda" => ["250", "RW"],
            "Saint Kitts and Nevis" => ["1869", "KN"],
            "Saint Lucia" => ["1758", "LC"],
            "Saint Vincent and the Grenadines" => ["1784", "VC"],
            "Samoa" => ["685", "WS"],
            "San Marino" => ["378", "SM"],
            "Sao Tome and Principe" => ["239", "ST"],
            "Saudi Arabia" => ["966", "SA"],
            "Senegal" => ["221", "SN"],
            "Serbia" => ["381", "RS"],
            "Seychelles" => ["248", "SC"],
            "Sierra Leone" => ["232", "SL"],
            "Singapore" => ["65", "SG"],
            "Slovakia" => ["421", "SK"],
            "Slovenia" => ["386", "SI"],
            "Solomon Islands" => ["677", "SB"],
            "Somalia" => ["252", "SO"],
            "South Africa" => ["27", "ZA"],
            "South Korea" => ["82", "KR"],
            "South Sudan" => ["211", "SS"],
            "Spain" => ["34", "ES"],
            "Sri Lanka" => ["94", "LK"],
            "Sudan" => ["249", "SD"],
            "Suriname" => ["597", "SR"],
            "Sweden" => ["46", "SE"],
            "Switzerland" => ["41", "CH"],
            "Syria" => ["963", "SY"],
            "Taiwan" => ["886", "TW"],
            "Tajikistan" => ["992", "TJ"],
            "Tanzania" => ["255", "TZ"],
            "Thailand" => ["66", "TH"],
            "Togo" => ["228", "TG"],
            "Tonga" => ["676", "TO"],
            "Trinidad and Tobago" => ["186", "TT"],
        ];

        foreach ($dialingCodes as $country => [$code, $countryCode]) {
            DialingCode::create([
                'country' => $country,
                'dialing_code' => $code,
                'country_code' => $countryCode,
            ]);
        }
    }
}
