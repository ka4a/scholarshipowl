<?php namespace App\Http\Controllers;

use App\Entity\Account;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

    use ValidatesRequests;
    use AuthorizesRequests;

    const SESSION_REGISTER_PREFIX = "REGISTER";

    /**
     * Return client defined 'accountId' parameter or authorized user.
     * Authorizes access to the account entity.
     *
     * @param Request $request
     * @param null    $id
     *
     * @return Account|null
     */
    protected function account(Request $request, $id = null)
    {
        if ($account = account($id !== null ? $id : $request->get('accountId'))) {
            $this->authorize('access', $account);

            return $account;
        }

        return null;
    }

    /**
     * Gets data from session during registration
     *
     * @access private
     * @return array
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    protected function getRegistrationData()
    {
        $result = array();
        $session = \Session::all();

        if(array_key_exists(self::SESSION_REGISTER_PREFIX, $session)) {
            $result = $session[self::SESSION_REGISTER_PREFIX];
        }

        return $result;
    }


    /**
     * @param array $default
     */
    protected function setRegistrationData(array $default = [])
    {
        $input = \Input::all() + $default;

        $input["agree_call"] = (empty($input["agree_call"]))?0:1;

        $fields = array(
            "birthday_date", "birthday_month", "birthday_day", "birthday_year", "gender", "school_level_id", "is_subscribed",
            "first_name", "last_name", "email", "phone", 'country_code', 'study_country',
            "ethnicity_id", "citizenship_id", "enrollment_month", "enrollment_year", "gpa", "degree_id", "degree_type_id", "career_goal_id", "study_online",
            "address", "address2", "city", "state_id", "state_name", "zip", "university", "highschool", "enrolled", "military_affiliation_id", "profile_type", "agree_call"
        );

        foreach($fields as $field) {
            if(array_key_exists($field, $input) && isset($input[$field])) {
                $key = sprintf("%s.%s", self::SESSION_REGISTER_PREFIX, $field);
                $value = $input[$field];

                \Session::put($key, $value);
            }
        }
    }
}
