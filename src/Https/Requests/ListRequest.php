<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/23
 * Time: 11:28
 */
namespace SimpleShop\User\Http\Requests;


use SimpleShop\Commons\Requests\Request;

class ListRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $params = $this->route()->parameters();

        if (! isset($params['order'])) {
            $params['order'] = 'id';
        }

        if (! isset($params['sort'])) {
            $params['sort'] = 'desc';
        }

        return $params;
    }
}