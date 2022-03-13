<?php


namespace App\Services\User;


use App\Repositories\User\GetUserJourneyRepository;
use App\Repositories\User\UsersRepository;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Collection;

class GetUserJourneyService
{
    private Factory $validator;
    private UsersRepository $usersRepository;
    private GetUserJourneyRepository $getUserJourneyRepository;

    public function __construct(
        Factory $validator,
        UsersRepository $usersRepository,
        GetUserJourneyRepository $getUserJourneyRepository
    ) {
        $this->validator = $validator;
        $this->usersRepository = $usersRepository;
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
            ["id" => function (string $attribute, int $value, callable $fail) {
                    if($value < 0){
                      $fail("positive");
                    }

                    if(!$this->usersRepository->userExistsById($value)){
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
