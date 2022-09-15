<?php

/**
 * url_builder()
 *
 * Creates URL with all GET params
 * Used for tracking all params
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

use ScholarshipOwl\Data\Service\Account\AccountService;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;
use App\Entity\Admin\AdminRole;

use App\Contracts\MappingTags;

$APP_REVISION = null;

if (!function_exists('map_tags')) {
    /**
     * @param string      $string
     * @param array       $tags     Hash table of 'tag' => 'value'
     * @param string|null $prefix   Prefix all the tags, example: 'account_first_name' instead of 'first_name'
     *
     * @return mixed|string
     */
    function map_tags(string $string, array $tags, string $prefix = null) {
        foreach ($tags as $tag => $val) {
            $string = str_replace(sprintf('[[%s]]', ($prefix ? $prefix . '_' : '') . $tag), $val, $string);
        }

        return $string;
    }
}

if (!function_exists('map_tags_providers')) {
    /**
     * In order to provider prefix to provider use ['prefix', $provider]
     *
     * @param string $string
     * @param array  $providers
     *
     * @return mixed|string
     */
    function map_tags_provider(string $string, array $providers)
    {
        foreach ($providers as $provider) {
            if ($provider instanceof MappingTags) {
                $string = map_tags($string, $provider->tags());
            } elseif (is_array($provider)) {
                if (count($provider) === 2 && is_string($provider[0]) && $provider[1] instanceof MappingTags) {
                    $string = map_tags($string, $provider[1]->tags(), $provider[0]);
                } else {
                    $string = map_tags($string, $provider);
                }
            } else {
                throw new \InvalidArgumentException('Provider should be array or MappingTags instance!');
            }
        }

        return $string;
    }
}

if (!function_exists('dashesToCamelCase')) {
    function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
}

if (!function_exists('yesno')) {
    function yesno(bool $bool) : string
    {
        return $bool ? 'Yes' : 'No';
    }
}

if (!function_exists('str_before')) {

    function str_before(string $needle, string $string)
    {
        return substr($string, 0, strpos($string, $needle));
    }
}

if (!function_exists('str_after')) {

    function str_after(string $needle, string $string)
    {
        return (false !== ($pos = strpos($string, $needle))) ?
            substr($string, $pos + strlen($needle)) : false;
    }
}

if (!function_exists('is_iterable')) {

    function is_iterable ($var)
    {
        return (is_array($var) || $var instanceof Traversable);
    }
}

function url_builder($url, $ssl = null) {
	$params = array();

	if (\Request::isMethod("get")) {
		$params = \Input::get();
		foreach ($params as $key => $value) {
			// Subdomain ???
			$firstChar = substr($key, 0, 1);
			if (in_array($firstChar, array("/", "\\"))) {
				unset($params[$key]);
			}
			else if (strtolower($key) == "upgrade" || strtolower($key) == "reapply") {
				// Payment popup only once
				unset($params[$key]);
			}
		}
	}

	if (!empty($params)) {
		$url .= "?" . http_build_query($params);
	}

	return url($url, array(), $ssl);
}

function getinfo($class, $id) {
    $infoClass = InfoServiceFactory::get($class);
    return $infoClass->getById($id, false);
}

function is_production() {
    return app()->environment('production');
}

function is_testing() {
	return app()->environment('testing');
}

/**
 * @deprecated
 * @return \App\Entity\Account
 */
function getLoggedUser() {
    $user = null;

    if ($user = \Auth::user()) {
        /**
         * @var \App\Entity\Account $user
         */
        $user = EntityManager::getRepository(\App\Entity\Account::class)
            ->findOneBy(['accountId' => $user->getAccountId()]);
    }

    return $user;
}

if (!function_exists('release')) {
    /**
     * @param string $default
     *
     * @return string
     */
    function revision($default = 'Unknown')
    {
        global $APP_REVISION;

        if ($APP_REVISION === null) {
            $file = base_path('REVISION');
            $APP_REVISION = file_exists($file) ? trim(file_get_contents($file)) : $default;
        }

        return $APP_REVISION;
    }
}

if (!function_exists('homepage')) {
    /**
     * @return string
     */
    function homepage()
    {
        return route(Auth::guest() ? 'homepage' : 'my-account');
    }
}

if (!function_exists('setting')) {
    /**
     * @param $name
     *
     * @return array|string
     */
    function setting($name)
    {
        $service = new \ScholarshipOwl\Data\Service\Website\SettingService();
        $result = $service->getSetting($name);

        if (!is_array($result)) {
            $result = str_replace(array("\\n", "\\r", "\\t"), "", trim($result, "\""));
        }

        return $result;
    }
}

if (!function_exists('setting_on')) {

    /**
     * Setting on or off
     *
     * @param $name
     *
     * @return bool
     */
    function setting_on($name)
    {
        return setting($name) === 'yes';
    }

}

if (!function_exists('set_checked')) {

    /**
     * Used for check/uncheck checkboxes
     *
     * Usage:
     *   <input type='checkbox' {{ setting_checked("setting_name") }} />
     *
     * @param $bool bool
     *
     * @return string
     */
    function set_checked($bool)
    {
        return $bool ? 'checked' : '';
    }

}

if (!function_exists('setting_checked')) {

    /**
     * Used for check/uncheck checkboxes by website setting.
     *
     * @param $name int Setting name
     *
     * @return string
     */
    function setting_checked($name)
    {
        return set_checked(setting_on($name));
    }

}

if (!function_exists('map_single_key')) {
    /**
     * @param $key
     *
     * @return Closure
     */
    function map_array_key($key) {
        return function(array $a) use ($key) {
            return $a[$key];
        };
    }
}

if (!function_exists('collection_merge')) {
    /**
     * @param array ...$args
     *
     * @return array
     */
    function collection_merge(...$args) {
        $args = array_filter($args);

        return empty($args) ? [] : call_user_func_array(
            'array_merge',
            array_map(
                function(\Doctrine\Common\Collections\Collection $collection) {
                    return $collection->toArray();
                },
                $args
            )
        );
    }
}

if (!function_exists('doctrine_dump')) {
    /**
     * @param mixed   $var       The variable to dump.
     * @param integer $maxDepth  The maximum nesting level for object properties.
     * @param boolean $stripTags Whether output should strip HTML tags.
     * @param boolean $echo      Send the dumped value to the output buffer
     *
     * @return string
     */
    function doctrine_dump($var, $maxDepth = 2, $stripTags = true, $echo = true) {
        return \Doctrine\Common\Util\Debug::dump($var, $maxDepth, $stripTags, $echo);
    }
}

if (!function_exists('tmp_file')) {
    /**
     * @param string      $prefix
     * @param string|null $dir
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    function tmp_file($prefix = '', $dir = null) : \Symfony\Component\HttpFoundation\File\File {
        return new \Symfony\Component\HttpFoundation\File\File(
            tempnam($dir ?: sys_get_temp_dir(), $prefix)
        );
    }
}

if (!function_exists('account')) {
    /**
     * @return \App\Entity\Account|null
     */
    function account($id = null) {
        if ($id !== null) {
            return \EntityManager::find(\App\Entity\Account::class, $id);
        }

        return \Auth::user() instanceof \App\Entity\Account ? \Auth::user() : null;
    }
}

/**
 * @param ScholarshipOwl\Data\Entity\Account\Account $user
 * @param string                                     $name
 *
 * @return mixed
 */
function popuptext($user, $name) {
    /** @var \App\Entity\Repository\ScholarshipRepository $repository */
    $repository = \EntityManager::getRepository(\App\Entity\Scholarship::class);

    /** @var \App\Entity\Account $account */
    $account = \EntityManager::find(\App\Entity\Account::class, $user->getAccountId());

	$message = setting($name);

	$scholarshipService = new \ScholarshipOwl\Data\Service\Scholarship\ScholarshipService();
	$scholarshipIds = $repository->findEligibleNotAppliedScholarshipsIds($account);
	$total = $scholarshipService->getScholarshipSummaryPrice($scholarshipIds);

	return str_replace(
		array("[name]", "[price]", "[scholarships]"),
		array($account->getUsername(), number_format($total) ,count($scholarshipIds)),
		$message
	);
}

function format_date($date, $short = true) {
	$result = "";

	if(!empty($date) && $date != "0000-00-00 00:00:00") {
		$result = $date;

		if($short == true) {
			$result = substr($result, 0, 10);
		}
	}

	return $result;
}

function handle_exception(\Exception $exception) {
	\Log::error($exception);

    if (config('services.sentry.phpReport')) {
        /** @var \Raven_Client $sentry */
        $sentry = app('sentry');
        $sentry->captureException($exception);
    }

    if (!App::environment('production')) {
		return;
	}

	try {
		$config = \Config::get("scholarshipowl.mail.system.exception");
		$subject = $config["subject"];
		$from = $config["from"];
		$to = $config["to"];

		if(!is_array($to)) {
			$to = array($to);
		}

		foreach($to as $recipient) {
			\Mail::queue(
				array("text" => "emails.system.exception"),
				array(
					"text" => $exception->getMessage(),
					"code" => $exception->getCode(),
					"file" => $exception->getFile(),
					"line" => $exception->getLine()
				),
				function($msg) use ($subject, $from, $recipient) {
					$msg->from($from[0], $from[1]);
					$msg->subject($subject);
					$msg->to($recipient);
				}
			);
		}
	}
	catch(\Exception $exc) {
		\Log::error($exception);
	}
}

/**
 * @param callable $callback
 * @param array    $array
 *
 * @return mixed
 *
 * @link http://php.net/manual/en/function.array-map.php#112857
 */
function array_map_recursive($callback, $array) {
	foreach ($array as $key => $value) {
		if (is_array($array[$key])) {
			$array[$key] = array_map_recursive($callback, $array[$key]);
		}
		else {
			$array[$key] = call_user_func($callback, $array[$key]);
		}
	}

	return $array;
}


function protectRestrictedValue ($value)
{
	if (strlen($value) > 4) {
		$value = substr($value, 0, 4) . '****';
	} else {
		$value = substr($value, 0, 1) . '***';
	}

	return $value;
}

function restricted_data($value, $isCollectionItem = false)
{
	if (null === ($admin = \Auth::user()) || !$admin instanceof \App\Entity\Admin\Admin) {
		return $value;
	}

	if ($admin->getAdminRole()->getAccessLevel() == AdminRole::LEVEL_ACCESS_RESTRICTED && $isCollectionItem) {
		$value = protectRestrictedValue($value);
	} else if ($admin->getAdminRole()->getAccessLevel() == AdminRole::LEVEL_ACCESS_NO_DATA) {
		$value = protectRestrictedValue($value);
	}

	return $value;
}

function logHasoffersAccount(\App\Entity\Account $account)
{
    return [
        'activeSubscription' => [],
        'account' => [
            "id" => $account->getAccountId(),
            "status" => $account->getAccountStatus()->getName(),
            "email" => $account->getEmail(),
            "firstName" => $account->getProfile()->getFirstName(),
            "lastName" => $account->getProfile()->getLastName(),
            "lastUpdate" => $account->getLastUpdatedDate(),
            "profileCompleteness" => $account->getProfile()->getCompleteness(),
        ],
    ];
}

function format_bytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
     $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function format_memory() {
    return format_bytes(memory_get_usage());
}

if (!function_exists('is_mobile')) {
    /**
     * @return bool
     */
    function is_mobile() {
        return \Agent::isMobile() || \Agent::isTablet();
    }
}

if (!function_exists('string_generate')) {
    /**
     * @param int $length
     *
     * @return string
     */
    function string_generate(int $length) {
        $string = '';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $strlen = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, $strlen - 1)];
        }
        return $string;
    }
}

if (!function_exists('features')) {
    /**
     * @return \App\Entity\FeatureSet
     */
    function features() {
        return \App\Entity\FeatureSet::config();
    }
}

if (!function_exists('company_details')) {
    /**
     * @return \App\Entity\FeatureCompanyDetailsSet
     */
    function company_details() {
        return \App\Entity\FeatureCompanyDetailsSet::config();
    }
}

if (!function_exists('array_utf8_encode')) {
    /**
     * Encode array to utf8 recursively
     *
     * @param $data
     *
     * @return array|string
     */
    function array_utf8_encode($data)
    {
        if (is_string($data))
            return html_entity_decode(mb_convert_encoding($data,"UTF-8","auto"));
        if (is_array($data)) {
            return array_map_recursive(function($value) { return array_utf8_encode($value); }, $data);
        }

        return $data;
    }
}
if (!function_exists('options')) {
    /**
     * @return App\Services\OptionsManager
     */
    function options() {
        return app(\App\Services\OptionsManager::class);
    }
}

if (!function_exists('timezone')) {
    /**
     * @return DateTimeZone
     */
    function timezone() {
        return new \DateTimeZone(config('app.timezone'));
    }
}
