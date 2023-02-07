<?php

namespace App\Http\Requests;

use App\Models\LinkDetails;
use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
{
    public function __construct(protected LinkDetails $linkDetails)
    {}
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'originalUrl' => ['required', 'max:255', 'string', 'regex:#^(http|https):\/\/#i'],
            'isPublic' => 'required|boolean',
            'recreate' => 'boolean'
        ];
    }

    public function getDetails()
    {
        $this->linkDetails->setIsPublic($this->input('isPublic'));
        $this->linkDetails->setOriginalUrl($this->input('originalUrl'));

        return $this->linkDetails;
    }
}
