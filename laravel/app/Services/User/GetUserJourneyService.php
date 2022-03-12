<?php


namespace App\Services\User;


use App\Repositories\User\GetUserJourneyRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Collection;

class GetUserJourneyService
{
    private Factory $validator;
    private UserRepository $userRepository;
    private GetUserJourneyRepository $getUserJourneyRepository;

    public function __construct(
        Factory $validator,
        UserRepository $userRepository,
        GetUserJourneyRepository $getUserJourneyRepository
    ) {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->getUserJourneyRepository = $getUserJourneyRepository;
    }

    public function get(int $id): Collection
    {
        ["id" => $id] = $this->validate($id);

        return $this->getUserJourneyRepository->get($id);
    }

    private function validate(int $id): array
    {
        return $this->validator->make(
            ["id" => $id],
            ["id" => "required",
                function (string $attribute, string $value, callable $fail) {
                    var_dump($value);
                    if($value < 0){
                      $fail("positive");
                    }

                    if(!$this->userRepository->userExistsById($value)){
                        $fail("user does not exist");
                    }
            }],
            [
                "id.required" => "required",
                "id.numeric" => "numeric",
            ]
        )->validate();
    }
}
