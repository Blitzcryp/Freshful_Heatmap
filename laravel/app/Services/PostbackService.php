<?php


namespace App\Services;

use App\Repositories\PostbackRepository;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;

class PostbackService
{
    private Factory $validator;
    private PostbackRepository $postbackRepository;
    private UserRepository $userRepository;

    public function __construct(
        Factory $validator,
        PostbackRepository $postbackRepository,
        UserRepository $userRepository
    ) {
        $this->validator = $validator;
        $this->postbackRepository = $postbackRepository;
        $this->userRepository = $userRepository;
    }

    public function stuff(array $input): bool
    {
        ["link" => $link, "linkType" => $linkType, "timestamp" => $timestamp, "uid" => $uid] = $this->validate($input);

        return $this->postbackRepository->writePostback(
            $link,
            $linkType,
            Carbon::createFromTimestamp($timestamp),
            $uid
        );
    }

    private function validate(array $input){
        return $this->validator->make($input, [
            "link" => [
                "required", "string"
            ],
            "linkType" => [
                "required", Rule::in(["product", "category", "static-page", "checkout", "homepage"])
            ],
            "timestamp" => [
                "required", "numeric"
            ],
            "uid" => [
                "required", "numeric", function (string $attribute, string $value, callable $fail) {
                    if($value < 0){
                        $fail("positive");
                    }

                    if(!$this->userRepository->userExistsById($value)){
                        $fail("user does not exist");
                    }
                }
            ]
        ], [
            "link.required" => "required",
            "link.string" => "string",
            "linkType.required" => "required",
            "linkType.in" => "invalid",
            "timestamp.required" => "required",
            "timestamp.numeric" => "numeric",
            "uid.required" => "required",
        ])->validate();
    }
}
