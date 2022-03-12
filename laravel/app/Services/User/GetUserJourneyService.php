<?php


namespace App\Services\User;


use App\Repositories\User\GetUserJourneyRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Collection;

class GetUserJourneyService
{
    private ResponseFactory $validator;
    private UserRepository $userRepository;
    private GetUserJourneyRepository $getUserJourneyRepository;

    public function __construct(
        ResponseFactory $validator,
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
            ["id" => "required", "numeric",
                function (string $attribute, string $value, callable $fail) {
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
