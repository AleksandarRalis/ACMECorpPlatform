<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CampaignStatus;

class UpsertCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'min:3',
            ],
            'description' => [
                'required',
                'string',
                'max:5000',
                'min:10',
            ],
            'category' => [
                'nullable',
                'string',
                'max:100',
            ],
            'goal_amount' => [
                'required',
                'numeric',
                'min:1',
                'max:999999.99',
            ],
            'image_url' => [
                'nullable',
                'url',
                'max:500',
            ],
            'start_date' => [
                'required',
                'date'
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
            ],
            'status' => [
                'nullable',
                'string',
                'in:active,inactive,completed,cancelled,pending',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Campaign title is required.',
            'title.min' => 'Campaign title must be at least 3 characters.',
            'title.max' => 'Campaign title cannot exceed 255 characters.',
            
            'description.required' => 'Campaign description is required.',
            'description.min' => 'Campaign description must be at least 10 characters.',
            'description.max' => 'Campaign description cannot exceed 5000 characters.',
            
            'goal_amount.required' => 'Goal amount is required.',
            'goal_amount.numeric' => 'Goal amount must be a valid number.',
            'goal_amount.min' => 'Goal amount must be at least $1.',
            'goal_amount.max' => 'Goal amount cannot exceed $999,999.99.',
            
            'image_url.url' => 'Please provide a valid image URL.',
            'image_url.max' => 'Image URL cannot exceed 500 characters.',
            
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Please provide a valid start date.',
            'start_date.after_or_equal' => 'Start date must be today or in the future.',
            
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'Please provide a valid end date.',
            'end_date.after' => 'End date must be after the start date.',
            'category.max' => 'Category cannot exceed 100 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Campaign Title',
            'description' => 'Campaign Description',
            'goal_amount' => 'Goal Amount',
            'image_url' => 'Image URL',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'category' => 'Category',
        ];
    }
} 