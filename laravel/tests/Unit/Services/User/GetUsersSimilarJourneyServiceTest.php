<?php

namespace Services\User;

use App\Repositories\User\GetUserJourneyRepository;
use App\Repositories\User\UsersRepository;
use App\Services\User\GetUsersSimilarJourneyService;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class GetUsersSimilarJourneyServiceTest extends TestCase
{
    public function test_it_works(){

        $usersRepository = $this->mock(UsersRepository::class);
        $usersRepository->shouldReceive("userExistsById")
            ->with(1)
            ->andReturn(true);

        $getUsersJourneyRepository = $this->mock(GetUserJourneyRepository::class);
        $getUsersJourneyRepository->shouldReceive("get")
            ->with(1)
            ->andReturn(
                collect([
                    (object)[
                        "id" => 1,
                        "uid" => 1,
                        "link" => "random-link-1",
                        "linkType" => "product",
                        "timestamp" => "2022,03-12 18:16:05"
                    ],
                    (object)[
                        "id" => 2,
                        "uid" => 1,
                        "link" => "random-link-2",
                        "linkType" => "product",
                        "timestamp" => "2022,03-12 18:16:06"
                    ]
                ])
            );
        $getUsersJourneyRepository->shouldReceive("getOtherUsersJourney")
            ->with(1, ["random-link-1", "random-link-2"])
            ->andReturn(
                collect([
                    (object)[
                        "id" => 1,
                        "uid" => 2,
                        "link" => "random-link-1",
                        "linkType" => "product",
                        "timestamp" => "2022,03-12 18:16:05"
                    ],
                    (object)[
                        "id" => 2,
                        "uid" => 2,
                        "link" => "random-link-2",
                        "linkType" => "product",
                        "timestamp" => "2022,03-12 18:16:06"
                    ],
                    (object)[
                        "id" => 1,
                        "uid" => 3,
                        "link" => "random-link-1",
                        "linkType" => "product",
                        "timestamp" => "2022,03-12 18:16:05"
                    ],
                    (object)[
                        "id" => 2,
                        "uid" => 3,
                        "link" => "random-link-2",
                        "linkType" => "product",
                        "timestamp" => "2022,03-12 18:16:06"
                    ],
                    (object)[
                        "id" => 2,
                        "uid" => 4,
                        "link" => "random-link-2",
                        "linkType" => "product",
                        "timestamp" => "2022,03-12 18:16:06"
                    ],
                    (object)[
                        "id" => 2,
                        "uid" => 5,
                        "link" => "random-link-2",
                        "linkType" => "product",
                        "timestamp" => "2022,03-12 18:16:06"
                    ]
                ])
            );

        /** @var GetUsersSimilarJourneyService $getUsersSimilarJourneyService */
        $getUsersSimilarJourneyService = $this->app->get(GetUsersSimilarJourneyService::class);

        $result = $getUsersSimilarJourneyService->get(1);

        $this->assertEquals(["users" => [2, 3]], $result);
    }

    /**
     * @dataProvider validationData
     */
    public function test_validator_works(array $data){
        ["id" => $id, "errors" => $errors] = $data;

        $userRepository = $this->mock(UsersRepository::class);
        $userRepository->shouldReceive("userExistsById")
            ->with(2)
            ->andReturn(false);
        $userRepository->shouldReceive("userExistsById")
            ->with(-1)
            ->andReturn(true);

        /** @var GetUsersSimilarJourneyService $getUsersSimilarJourneyService */
        $getUsersSimilarJourneyService = $this->app->get(GetUsersSimilarJourneyService::class);

        try {
            $getUsersSimilarJourneyService->get($id);
        } catch (ValidationException $e){
            $this->assertEquals($errors, $e->validator->errors()->messages());
        }
    }

    public function validationData(): array
    {
        return [
            "test #1 - id is negative" => [
                [
                    "id" => -1,
                    "errors" => [
                        "id" => ["positive"]
                    ]
                ]
            ],
            "test #2 - user doesn't exist by id" => [
                [
                    "id" => 2,
                    "errors" => [
                        "id" => ["user does not exist"]
                    ]
                ]
            ]
        ];
    }
}
