<?php


namespace App\Services;

use App\Enums\LinksTypeEnums;
use App\Repositories\PostbackRepository;
use App\Repositories\User\UsersRepository;
use Carbon\Carbon;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;

class PostbackService
{
    private Factory $validator;
    private PostbackRepository $postbackRepository;
    private UsersRepository $userRepository;

    public function __construct(
        Factory $validator,
        PostbackRepository $postbackRepository,
        UsersRepository $userRepository
    ) {
        $this->validator = $validator;
        $this->postbackRepository = $postbackRepository;
        $this->userRepository = $userRepository;
    }

    public function postback(array $input): bool
    {
        ["link" => $link, "linkType" => $linkType, "timestamp" => $timestamp, "uid" => $uid] = $this->validate($input);

        return $this->postbackRepository->createRollup(
            $link,
            $linkType,
            Carbon::createFromTimestamp($timestamp)->toDateTimeString(),
            $uid
        );
    }

    private function validate(array $input): array
    {
        return $this->validator->make($input, [
            "link" => ["required", "string"],
            "linkType" => ["required", Rule::in(LinksTypeEnums::cases())],
            "timestamp" => ["required", "numeric"],
            "uid" => [
                "required", "numeric",
                function (string $attribute, string $value, callable $fail) {
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
