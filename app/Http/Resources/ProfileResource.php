<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'headline' => $this->headline,
            'bio' => $this->bio,
            'avatar' => $this->avatar,
            'resume_url' => $this->resume_url,
            'location' => $this->location,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'timezone' => $this->timezone,
            'social_links' => $this->social_links,
            'experiences' => ExperienceResource::collection($this->whenLoaded('experiences')),
            'educations' => EducationResource::collection($this->whenLoaded('educations')),
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'certifications' => CertificationResource::collection($this->whenLoaded('certifications')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}