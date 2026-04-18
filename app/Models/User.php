<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{

	use HasApiTokens;
	use HasRoles;
	use Notifiable;


	public $appends = ['full_name', 'full_phone'];

	public function getFullNameAttribute()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	public function getFullPhoneAttribute()
	{
		// Extract country code from ccm (e.g., "United Arab Emirates (+971)")
		preg_match('/\((\+\d+)\)/', $this->ccm, $matches);
		$code = $matches[1] ?? '';

		// Combine code with phone number or return '-' if null
		return $code . ($this->phone_number ?? '-');
	}

	protected $fillable = [
		'first_name',
		'last_name',
		'type',
		'email',
		'password',
		'phone_number',
		'profile',
		'lang',
		'subscription',
		'subscription_expire_date',
		'parent_id',
		'is_active',
		'fax',
		'po_box',
		'mobile',
		'ccp',
		'ccm',
		'title',
		'country',
		'gst',
		'isModified',
		'IsBCToPortalIntegrated',
		'BCToPortalIntegratedTime',
		'IsPortalToBCIntegrated',
		'PortalToBCIntegratedTime',
		'isDeleted'
	];


	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function totalUser()
	{
		return User::whereNotIn('type', ['client'])->where('parent_id', $this->id)->count();
	}
	public function totalClient()
	{
		return User::where('type', 'client')->where('parent_id', $this->id)->count();
	}

	public function totalTechnician()
	{
		return User::where('type', 'technician')->where('parent_id', $this->id)->count();
	}

	public function totalContact()
	{
		return Contact::where('parent_id', '=', parentId())->count();
	}

	public function roleWiseUserCount($role)
	{
		return User::where('type', $role)->where('parent_id', parentId())->count();
	}

	public function workHours()
	{
		return $this->hasMany(TechnicianWorkHours::class, 'technician_id');
	}

	public function bookings()
	{
		return $this->hasMany(Booking::class, 'client', 'id');
	}

	public function billInfo()
	{
		return $this->hasOne(Booking::class, 'client', 'id')->latest('id');
	}

	public static function getDevice($user)
	{
		$mobileType = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
		$tabletType = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
		if (preg_match_all($mobileType, $user)) {
			return 'mobile';
		} else {
			if (preg_match_all($tabletType, $user)) {
				return 'tablet';
			} else {
				return 'desktop';
			}
		}
	}

	public function subscriptions()
	{
		return $this->hasOne('App\Models\Subscription', 'id', 'subscription');
	}
	public function vechiles()
	{
		return $this->hasMany(User::class, 'client', 'id');
	}
	public function clients()
	{
		return $this->hasOne('App\Models\ClientDetail', 'user_id', 'id');
	}
	public function services()
	{
		return $this->belongsToMany(ServiceGroups::class, 'technician_services');
	}

	public function tech_skill()
	{
		return $this->hasOne('\App\Models\Skill', 'user_id', 'id');
	}

	public function tech_skillgroup()
	{
		return $this->hasOne('\App\Models\SkillGroup', 'id', 'skills');
	}
	public function skillgroups()
	{
		return $this->belongsToMany(SkillGroup::class, 'technician_skills', 'user_id');
	}

	public function technicianlocation()
	{
		return $this->hasOne(TechnicianLocation::class, 'user_id', 'id');
	}
	public function invoice()
	{
		return $this->hasOne(Invoice::class, 'client');
	}
	public function shiftmaster()
	{
		return $this->hasOne(ShiftMaster::class, 'id', 'shift')->withDefault();
	}
	public function warehouses()
	{
		return $this->belongsToMany(WarHouse::class, 'user_warehouse');
	}
	public function warehouse()
	{
		return $this->hasOne(WarHouse::class, 'technicians');
	}
	public function customerTemplate()
	{
		return $this->belongsTo(CustomerTemplate::class, 'customer_template', 'id');
	}

	public function technicianservices()
	{
		return $this->belongsToMany(ServiceGroups::class, 'technician_service', 'user_id', 'service_id');
	}

	public function warrantyRegistrations()
	{
		return $this->hasMany(Warranty_registration::class, 'customer_id', 'id');
	}

	public function lastMessageWithAuth()
	{
		return $this->hasMany(Message::class, 'sender_id')
			->orWhere('receiver_id', $this->id);
	}

	public function clientDetails()
	{
		return $this->hasOne(ClientDetail::class, 'user_id');
	}

	public static $systemModules = [
		'dashboard',
		'chat',
		'mail',
		'customer',
		'booking',
		'quotation',
		'work order',
		'invoice',
		'payment',
		'service & part',
		'service',
		'adjustment',
		'adjustment report',
		'inventory',
		'service group',
		'warehouse',
		'item with vehicle',
		'transfer order',
		'vehicle',
		'technician',
		'warranty registration',
		'warranty claim',
		'warranty extend',
		'vehicle make',
		'vehicle model',
		'skill',
		'skill group',
		'uom',
		'regionalspecs',
		'customer group',
		'enginespecs',
		'brand',
		'origin',
		'item category',
		'customer template',
		'shift',
		'settings',
		'user',
		'role',
		'reset password',
		'technician dashboard'
	];

	public static $title = [
		'Dr.' => 'Dr.',
		'Mr.' => 'Mr.',
		'Mrs.' => 'Mrs.',
		'Miss.' => 'Miss.',
		'Ms.' => 'Ms.',
	];

	public static $country_code = [
		'United States (+1)' => 'United States (+1)',
		'Russian Federation (+7)' => 'Russian Federation (+7)',
		'Egypt (+20)' => 'Egypt (+20)',
		'South Africa (+27)' => 'South Africa (+27)',
		'Greece (+30)' => 'Greece (+30)',
		'Netherlands (+31)' => 'Netherlands (+31)',
		'Belgium (+32)' => 'Belgium (+32)',
		'France (+33)' => 'France (+33)',
		'Spain (+34)' => 'Spain (+34)',
		'Hungary (+36)' => 'Hungary (+36)',
		'Italy (+39)' => 'Italy (+39)',
		'Romania (+40)' => 'Romania (+40)',
		'Switzerland (+41)' => 'Switzerland (+41)',
		'Austria (+43)' => 'Austria (+43)',
		'United Kingdom (+44)' => 'United Kingdom (+44)',
		'Denmark (+45)' => 'Denmark (+45)',
		'Sweden (+46)' => 'Sweden (+46)',
		'Norway (+47)' => 'Norway (+47)',
		'Poland (+48)' => 'Poland (+48)',
		'Germany (+49)' => 'Germany (+49)',
		'Peru (+51)' => 'Peru (+51)',
		'Mexico (+52)' => 'Mexico (+52)',
		'Cuba (+53)' => 'Cuba (+53)',
		'Argentina (+54)' => 'Argentina (+54)',
		'Brazil (+55)' => 'Brazil (+55)',
		'Chile (+56)' => 'Chile (+56)',
		'Colombia (+57)' => 'Colombia (+57)',
		'Venezuela (+58)' => 'Venezuela (+58)',
		'Malaysia (+60)' => 'Malaysia (+60)',
		'Australia (+61)' => 'Australia (+61)',
		'Indonesia (+62)' => 'Indonesia (+62)',
		'Philippines (+63)' => 'Philippines (+63)',
		'New Zealand (+64)' => 'New Zealand (+64)',
		'Singapore (+65)' => 'Singapore (+65)',
		'Thailand (+66)' => 'Thailand (+66)',
		'Japan (+81)' => 'Japan (+81)',
		'Korea- Republic of (+82)' => 'Korea- Republic of (+82)',
		'Viet Nam (+84)' => 'Viet Nam (+84)',
		'China (+86)' => 'China (+86)',
		'Turkey (+90)' => 'Turkey (+90)',
		'India (+91)' => 'India (+91)',
		'Pakistan (+92)' => 'Pakistan (+92)',
		'Afghanistan (+93)' => 'Afghanistan (+93)',
		'Sri Lanka (+94)' => 'Sri Lanka (+94)',
		'Myanmar (+95)' => 'Myanmar (+95)',
		'Iran (+98)' => 'Iran (+98)',
		'Morocco (+212)' => 'Morocco (+212)',
		'Algeria (+213)' => 'Algeria (+213)',
		'Tunisia (+216)' => 'Tunisia (+216)',
		'Libya (+218)' => 'Libya (+218)',
		'Gambia (+220)' => 'Gambia (+220)',
		'Senegal (+221)' => 'Senegal (+221)',
		'Mauritania (+222)' => 'Mauritania (+222)',
		'Mali (+223)' => 'Mali (+223)',
		'Guinea (+224)' => 'Guinea (+224)',
		'Cote D\'Ivoire (+225)' => 'Cote D\'Ivoire (+225)',
		'Burkina Faso (+226)' => 'Burkina Faso (+226)',
		'Niger (+227)' => 'Niger (+227)',
		'Togo (+228)' => 'Togo (+228)',
		'Benin (+229)' => 'Benin (+229)',
		'Mauritius (+230)' => 'Mauritius (+230)',
		'Liberia (+231)' => 'Liberia (+231)',
		'Sierra Leone (+232)' => 'Sierra Leone (+232)',
		'Ghana (+233)' => 'Ghana (+233)',
		'Nigeria (+234)' => 'Nigeria (+234)',
		'Chad (+235)' => 'Chad (+235)',
		'Central African Republic (+236)' => 'Central African Republic (+236)',
		'Cameroon (+237)' => 'Cameroon (+237)',
		'Cape Verde (+238)' => 'Cape Verde (+238)',
		'Sao Tome and Principe (+239)' => 'Sao Tome and Principe (+239)',
		'Equatorial Guinea (+240)' => 'Equatorial Guinea (+240)',
		'Gabon (+241)' => 'Gabon (+241)',
		'Congo (+242)' => 'Congo (+242)',
		'Congo- Democratic Republic of the (+243)' => 'Congo- Democratic Republic of the (+243)',
		'Angola (+244)' => 'Angola (+244)',
		'Guinea-Bissau (+245)' => 'Guinea-Bissau (+245)',
		'British Indian Ocean Territory (+246)' => 'British Indian Ocean Territory (+246)',
		'Ascension Island (+247)' => 'Ascension Island (+247)',
		'Seychelles (+248)' => 'Seychelles (+248)',
		'Sudan (+249)' => 'Sudan (+249)',
		'Rwanda (+250)' => 'Rwanda (+250)',
		'Ethiopia (+251)' => 'Ethiopia (+251)',
		'Somalia (+252)' => 'Somalia (+252)',
		'Djibouti (+253)' => 'Djibouti (+253)',
		'Kenya (+254)' => 'Kenya (+254)',
		'Tanzania (+255)' => 'Tanzania (+255)',
		'Uganda (+256)' => 'Uganda (+256)',
		'Burundi (+257)' => 'Burundi (+257)',
		'Mozambique (+258)' => 'Mozambique (+258)',
		'Zambia (+260)' => 'Zambia (+260)',
		'Madagascar (+261)' => 'Madagascar (+261)',
		'French Southern and Antarctic Lands (+262)' => 'French Southern and Antarctic Lands (+262)',
		'Zimbabwe (+263)' => 'Zimbabwe (+263)',
		'Namibia (+264)' => 'Namibia (+264)',
		'Malawi (+265)' => 'Malawi (+265)',
		'Lesotho (+266)' => 'Lesotho (+266)',
		'Botswana (+267)' => 'Botswana (+267)',
		'Swaziland (+268)' => 'Swaziland (+268)',
		'Comoros (+269)' => 'Comoros (+269)',
		'Saint Helena (+290)' => 'Saint Helena (+290)',
		'Eritrea (+291)' => 'Eritrea (+291)',
		'Aruba (+297)' => 'Aruba (+297)',
		'Faroe Islands (+298)' => 'Faroe Islands (+298)',
		'Greenland (+299)' => 'Greenland (+299)',
		'Gibraltar (+350)' => 'Gibraltar (+350)',
		'Portugal (+351)' => 'Portugal (+351)',
		'Luxembourg (+352)' => 'Luxembourg (+352)',
		'Ireland (+353)' => 'Ireland (+353)',
		'Iceland (+354)' => 'Iceland (+354)',
		'Albania (+355)' => 'Albania (+355)',
		'Malta (+356)' => 'Malta (+356)',
		'Cyprus- Republic of (+357)' => 'Cyprus- Republic of (+357)',
		'Finland (+358)' => 'Finland (+358)',
		'Lithuania (+370)' => 'Lithuania (+370)',
		'Latvia (+371)' => 'Latvia (+371)',
		'Estonia (+372)' => 'Estonia (+372)',
		'Moldova (+373)' => 'Moldova (+373)',
		'Armenia (+374)' => 'Armenia (+374)',
		'Belarus (+375)' => 'Belarus (+375)',
		'Andorra (+376)' => 'Andorra (+376)',
		'Monaco (+377)' => 'Monaco (+377)',
		'San Marino (+378)' => 'San Marino (+378)',
		'Ukraine (+380)' => 'Ukraine (+380)',
		'Serbia (+381)' => 'Serbia (+381)',
		'Montenegro (+382)' => 'Montenegro (+382)',
		'Croatia (+385)' => 'Croatia (+385)',
		'Slovenia (+386)' => 'Slovenia (+386)',
		'Bosnia and Herzegovina (+387)' => 'Bosnia and Herzegovina (+387)',
		'Macedonia (+389)' => 'Macedonia (+389)',
		'Czech Republic (+420)' => 'Czech Republic (+420)',
		'Slovakia (+421)' => 'Slovakia (+421)',
		'Liechtenstein (+423)' => 'Liechtenstein (+423)',
		'Falkland Islands (+500)' => 'Falkland Islands (+500)',
		'Belize (+501)' => 'Belize (+501)',
		'Guatemala (+502)' => 'Guatemala (+502)',
		'El Salvador (+503)' => 'El Salvador (+503)',
		'Honduras (+504)' => 'Honduras (+504)',
		'Nicaragua (+505)' => 'Nicaragua (+505)',
		'Costa Rica (+506)' => 'Costa Rica (+506)',
		'Panama (+507)' => 'Panama (+507)',
		'Saint Pierre and Miquelon (+508)' => 'Saint Pierre and Miquelon (+508)',
		'Haiti (+509)' => 'Haiti (+509)',
		'Guadeloupe (+590)' => 'Guadeloupe (+590)',
		'Bolivia (+591)' => 'Bolivia (+591)',
		'Guyana (+592)' => 'Guyana (+592)',
		'Ecuador (+593)' => 'Ecuador (+593)',
		'French Guiana (+594)' => 'French Guiana (+594)',
		'Paraguay (+595)' => 'Paraguay (+595)',
		'Martinique (+596)' => 'Martinique (+596)',
		'Suriname (+597)' => 'Suriname (+597)',
		'Uruguay (+598)' => 'Uruguay (+598)',
		'Netherlands Antilles (+599)' => 'Netherlands Antilles (+599)',
		'Timor-Leste (+670)' => 'Timor-Leste (+670)',
		'Norfolk Island (+672)' => 'Norfolk Island (+672)',
		'Brunei Darussalam (+673)' => 'Brunei Darussalam (+673)',
		'Nauru (+674)' => 'Nauru (+674)',
		'Papua New Guinea (+675)' => 'Papua New Guinea (+675)',
		'Tonga (+676)' => 'Tonga (+676)',
		'Solomon Islands (+677)' => 'Solomon Islands (+677)',
		'Vanuatu (+678)' => 'Vanuatu (+678)',
		'Fiji (+679)' => 'Fiji (+679)',
		'Palau (+680)' => 'Palau (+680)',
		'Wallis and Futuna Islands (+681)' => 'Wallis and Futuna Islands (+681)',
		'Cook Islands (+682)' => 'Cook Islands (+682)',
		'Niue (+683)' => 'Niue (+683)',
		'Samoa (+685)' => 'Samoa (+685)',
		'Kiribati (+686)' => 'Kiribati (+686)',
		'New Caledonia (+687)' => 'New Caledonia (+687)',
		'Tuvalu (+688)' => 'Tuvalu (+688)',
		'French Polynesia (+689)' => 'French Polynesia (+689)',
		'Tokelau (+690)' => 'Tokelau (+690)',
		'Micronesia (+691)' => 'Micronesia (+691)',
		'Marshall Islands (+692)' => 'Marshall Islands (+692)',
		'United States Minor Outlying Islands (+699)' => 'United States Minor Outlying Islands (+699)',
		'Korea- Democratic People\'s Republic of (+850)' => 'Korea- Democratic People\'s Republic of (+850)',
		'Hong Kong (+852)' => 'Hong Kong (+852)',
		'Macao (+853)' => 'Macao (+853)',
		'Cambodia (+855)' => 'Cambodia (+855)',
		'Laos (+856)' => 'Laos (+856)',
		'Pitcairn (+872)' => 'Pitcairn (+872)',
		'Bangladesh (+880)' => 'Bangladesh (+880)',
		'Taiwan (+886)' => 'Taiwan (+886)',
		'Maldives (+960)' => 'Maldives (+960)',
		'Lebanon (+961)' => 'Lebanon (+961)',
		'Jordan (+962)' => 'Jordan (+962)',
		'Syria (+963)' => 'Syria (+963)',
		'Iraq (+964)' => 'Iraq (+964)',
		'Kuwait (+965)' => 'Kuwait (+965)',
		'Saudi Arabia (+966)' => 'Saudi Arabia (+966)',
		'Yemen (+967)' => 'Yemen (+967)',
		'Oman (+968)' => 'Oman (+968)',
		'Palestine (+970)' => 'Palestine (+970)',
		'United Arab Emirates (+971)' => 'United Arab Emirates (+971)',
		'Israel (+972)' => 'Israel (+972)',
		'Bahrain (+973)' => 'Bahrain (+973)',
		'Qatar (+974)' => 'Qatar (+974)',
		'Bhutan (+975)' => 'Bhutan (+975)',
		'Mongolia (+976)' => 'Mongolia (+976)',
		'Nepal (+977)' => 'Nepal (+977)',
		'Tajikistan (+992)' => 'Tajikistan (+992)',
		'Turkmenistan (+993)' => 'Turkmenistan (+993)',
		'Azerbaijan (+994)' => 'Azerbaijan (+994)',
		'Georgia (+995)' => 'Georgia (+995)',
		'Kyrgyz Republic (+996)' => 'Kyrgyz Republic (+996)',
		'Uzbekistan (+998)' => 'Uzbekistan (+998)',
	];

	public static $country = [

		'AE' => 'United Arab Emirates',
		'AD' => 'Andorra',
		'AF' => 'Afghanistan',
		'AG' => 'Antigua and Barbuda',
		'AI' => 'Anguilla',
		'AL' => 'Albania',
		'AM' => 'Armenia',
		'AN' => 'Netherlands Antilles',
		'AO' => 'Angola',
		'AQ' => 'Antarctica',
		'AR' => 'Argentina',
		'AS' => 'American Samoa',
		'AT' => 'Austria',
		'AU' => 'Australia',
		'AW' => 'Aruba',
		'AZ' => 'Azerbaijan',
		'BA' => 'Bosnia and Herzegovina',
		'BB' => 'Barbados',
		'BD' => 'Bangladesh',
		'BE' => 'Belgium',
		'BF' => 'Burkina Faso',
		'BG' => 'Bulgaria',
		'BH' => 'Bahrain',
		'BI' => 'Burundi',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BN' => 'Brunei',
		'BO' => 'Bolivia',
		'BR' => 'Brazil',
		'BS' => 'Bahamas',
		'BT' => 'Bhutan',
		'BV' => 'Bouvet Island',
		'BW' => 'Botswana',
		'BY' => 'Belarus',
		'BZ' => 'Belize',
		'CA' => 'Canada',
		'CC' => 'Cocos [Keeling] Islands',
		'CD' => 'Congo [DRC]',
		'CF' => 'Central African Republic',
		'CG' => 'Congo [Republic]',
		'CH' => 'Switzerland',
		'CI' => 'Côte d Ivoire',
		'CK' => 'Cook Islands',
		'CL' => 'Chile',
		'CM' => 'Cameroon',
		'CN' => 'China',
		'CO' => 'Colombia',
		'CR' => 'Costa Rica',
		'CU' => 'Cuba',
		'CV' => 'Cape Verde',
		'CX' => 'Christmas Island',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DE' => 'Germany',
		'DJ' => 'Djibouti',
		'DK' => 'Denmark',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'DZ' => 'Algeria',
		'EC' => 'Ecuador',
		'EE' => 'Estonia',
		'EG' => 'Egypt',
		'EH' => 'Western Sahara',
		'ER' => 'Eritrea',
		'ES' => 'Spain',
		'ET' => 'Ethiopia',
		'FI' => 'Finland',
		'FJ' => 'Fiji',
		'FK' => 'Falkland Islands [Islas Malvinas]',
		'FM' => 'Micronesia',
		'FO' => 'Faroe Islands',
		'FR' => 'France',
		'GA' => 'Gabon',
		'GB' => 'United Kingdom',
		'GD' => 'Grenada',
		'GE' => 'Georgia',
		'GF' => 'French Guiana',
		'GG' => 'Guernsey',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GL' => 'Greenland',
		'GM' => 'Gambia',
		'GN' => 'Guinea',
		'GP' => 'Guadeloupe',
		'GQ' => 'Equatorial Guinea',
		'GR' => 'Greece',
		'GS' => 'South Georgia and the South Sandwich Islands',
		'GT' => 'Guatemala',
		'GU' => 'Guam',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'GZ' => 'Gaza Strip',
		'HK' => 'Hong Kong',
		'HM' => 'Heard Island and McDonald Islands',
		'HN' => 'Honduras',
		'HR' => 'Croatia',
		'HT' => 'Haiti',
		'HU' => 'Hungary',
		'ID' => 'Indonesia',
		'IE' => 'Ireland',
		'IL' => 'Israel',
		'IM' => 'Isle of Man',
		'IN' => 'India',
		'IO' => 'British Indian Ocean Territory',
		'IQ' => 'Iraq',
		'IR' => 'Iran',
		'IS' => 'Iceland',
		'IT' => 'Italy',
		'JE' => 'Jersey',
		'JM' => 'Jamaica',
		'JO' => 'Jordan',
		'JP' => 'Japan',
		'KE' => 'Kenya',
		'KG' => 'Kyrgyzstan',
		'KH' => 'Cambodia',
		'KI' => 'Kiribati',
		'KM' => 'Comoros',
		'KN' => 'Saint Kitts and Nevis',
		'KP' => 'North Korea',
		'KR' => 'South Korea',
		'KW' => 'Kuwait',
		'KY' => 'Cayman Islands',
		'KZ' => 'Kazakhstan',
		'LA' => 'Laos',
		'LB' => 'Lebanon',
		'LC' => 'Saint Lucia',
		'LI' => 'Liechtenstein',
		'LK' => 'Sri Lanka',
		'LR' => 'Liberia',
		'LS' => 'Lesotho',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'LV' => 'Latvia',
		'LY' => 'Libya',
		'MA' => 'Morocco',
		'MC' => 'Monaco',
		'MD' => 'Moldova',
		'ME' => 'Montenegro',
		'MG' => 'Madagascar',
		'MH' => 'Marshall Islands',
		'MK' => 'Macedonia [FYROM]',
		'ML' => 'Mali',
		'MM' => 'Myanmar [Burma]',
		'MN' => 'Mongolia',
		'MO' => 'Macau',
		'MP' => 'Northern Mariana Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MS' => 'Montserrat',
		'MT' => 'Malta',
		'MU' => 'Mauritius',
		'MV' => 'Maldives',
		'MW' => 'Malawi',
		'MX' => 'Mexico',
		'MY' => 'Malaysia',
		'MZ' => 'Mozambique',
		'NA' => 'Namibia',
		'NC' => 'New Caledonia',
		'NE' => 'Niger',
		'NF' => 'Norfolk Island',
		'NG' => 'Nigeria',
		'NI' => 'Nicaragua',
		'NL' => 'Netherlands',
		'NO' => 'Norway',
		'NP' => 'Nepal',
		'NR' => 'Nauru',
		'NU' => 'Niue',
		'NZ' => 'New Zealand',
		'OM' => 'Oman',
		'PA' => 'Panama',
		'PE' => 'Peru',
		'PF' => 'French Polynesia',
		'PG' => 'Papua New Guinea',
		'PH' => 'Philippines',
		'PK' => 'Pakistan',
		'PL' => 'Poland',
		'PM' => 'Saint Pierre and Miquelon',
		'PN' => 'Pitcairn Islands',
		'PR' => 'Puerto Rico',
		'PS' => 'Palestinian Territories',
		'PT' => 'Portugal',
		'PW' => 'Palau',
		'PY' => 'Paraguay',
		'QA' => 'Qatar',
		'RE' => 'Réunion',
		'RO' => 'Romania',
		'RS' => 'Serbia',
		'RU' => 'Russia',
		'RW' => 'Rwanda',
		'SA' => 'Saudi Arabia',
		'SB' => 'Solomon Islands',
		'SC' => 'Seychelles',
		'SD' => 'Sudan',
		'SE' => 'Sweden',
		'SG' => 'Singapore',
		'SH' => 'Saint Helena',
		'SI' => 'Slovenia',
		'SJ' => 'Svalbard and Jan Mayen',
		'SK' => 'Slovakia',
		'SL' => 'Sierra Leone',
		'SM' => 'San Marino',
		'SN' => 'Senegal',
		'SO' => 'Somalia',
		'SR' => 'Suriname',
		'ST' => 'São Tomé and Príncipe',
		'SV' => 'El Salvador',
		'SY' => 'Syria',
		'SZ' => 'Eswatini(Swaziland)',
		'TC' => 'Turks and Caicos Islands',
		'TD' => 'Chad',
		'TF' => 'French Southern Territories',
		'TG' => 'Togo',
		'TH' => 'Thailand',
		'TJ' => 'Tajikistan',
		'TK' => 'Tokelau',
		'TL' => 'Timor-Leste',
		'TM' => 'Turkmenistan',
		'TN' => 'Tunisia',
		'TO' => 'Tonga',
		'TR' => 'Turkey',
		'TT' => 'Trinidad and Tobago',
		'TV' => 'Tuvalu',
		'TW' => 'Taiwan',
		'TZ' => 'Tanzania',
		'UA' => 'Ukraine',
		'UG' => 'Uganda',
		'UM' => 'U.S. Minor Outlying Islands',
		'US' => 'United States',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VA' => 'Vatican City',
		'VC' => 'Saint Vincent and the Grenadines',
		'VE' => 'Venezuela',
		'VG' => 'British Virgin Islands',
		'VI' => 'U.S. Virgin Islands',
		'VN' => 'Vietnam',
		'VU' => 'Vanuatu',
		'WF' => 'Wallis and Futuna',
		'WS' => 'Samoa',
		'XK' => 'Kosovo',
		'YE' => 'Yemen',
		'YT' => 'Mayotte',
		'ZA' => 'South Africa',
		'ZM' => 'Zambia',
	];
}
