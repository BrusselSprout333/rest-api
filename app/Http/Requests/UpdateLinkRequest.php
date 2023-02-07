<?php

namespace App\Http\Requests;

use App\Models\LinkDetails;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Link;

class UpdateLinkRequest extends FormRequest
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
            'originalUrl' => ['string', 'max:255', 'regex:#^(http|https):\/\/#i'],
            'isPublic' => 'boolean',
            'shortCode' => 'string|min:5|max:15'
        ];
    }

    public function getDetails()
    {
        $this->linkDetails->setIsPublic($this->input('isPublic'));
        $this->linkDetails->setOriginalUrl($this->input('originalUrl'));

        return $this->linkDetails;
    }
}
