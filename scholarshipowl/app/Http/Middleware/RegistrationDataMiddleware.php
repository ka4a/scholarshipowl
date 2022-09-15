<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegistrationDataMiddleware
{
    const SESSION_PREFIX = "REGISTER";
    const SESSION_SOURCE = '_REGISTRATION_SOURCE';
    const PARAM_SOURCE = '_source';

    protected $fields = [
        "pfn" => "first_name",
        "pln" => "last_name",
        "pe"  => "email",
        "pp"  => "phone",
        "pd"  => "birthday",
        "pa"  => "address",
        "pz"  => "zip",
        "pg"  => "gender",
        "psl"  => "school_level_id",
        "pde"  => "degree_id",
    ];

    /**
     * @param string $default
     *
     * @return mixed
     */
    public static function source($default = 'unknown')
    {
        return \Session::get(static::SESSION_SOURCE, $default);
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return $this|RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        try {
            $filled = [];

            if ($request->has(static::PARAM_SOURCE)) {
                \Session::put(static::SESSION_SOURCE, $request->get(static::PARAM_SOURCE));

                $filled[] = static::PARAM_SOURCE;
            }

            foreach ($this->fields as $key => $value) {
                if ($item = $request->get($key, null)) {
                    $filled[] = $key;

                    $item = html_entity_decode($item);

                    if ($key == "pd") {
                        $dt = Carbon::createFromFormat("m-d-Y", $item);
                        $sessionKey = sprintf("%s.%s", self::SESSION_PREFIX, "birthday_month");
                        \Session::put($sessionKey, $dt->month);
                        $sessionKey = sprintf("%s.%s", self::SESSION_PREFIX, "birthday_day");
                        \Session::put($sessionKey, $dt->day);
                        $sessionKey = sprintf("%s.%s", self::SESSION_PREFIX, "birthday_year");
                        \Session::put($sessionKey, $dt->year);
                        continue;
                    }

                    $sessionKey = sprintf("%s.%s", self::SESSION_PREFIX, $value);
                    \Session::put($sessionKey, $item);
                }
            }

            if (!empty($filled) && $request->isMethod(Request::METHOD_GET)) {
                return $this->redirectWithoutParams($request, $filled);
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @param $params
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithoutParams($request, $params)
    {
        $qs = http_build_query($request->except($params));
        return \Redirect::to(\URL::current() . (empty($qs) ? '' : "?$qs"));
    }
}
