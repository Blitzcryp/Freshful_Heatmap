<?php

namespace Services\User;

use App\Repositories\User\GetUserJourneyRepository;
use App\Repositories\User\UserRepository;
use App\Services\User\GetUserJourneyService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class GetUserJourneyServiceTest extends TestCase
{
    public function test_it_works(){

        $getUserJourneyRepository = $this->mock(GetUserJourneyRepository::class);
        $getUserJourneyRepository->shouldReceive("get")
            ->with(1)
            ->andReturn(
              collect([
                  "id" => 1,
                  "uid" => 1,
                  "link" => "random-link",
                  "linkType" => "product",
                  "timestamp" => "2022,03-12 18:16:05"
              ])
            );

        /** @var GetUserJourneyService $getUserJourneyService */
        $getUserJourneyService = $this->app->get(GetUserJourneyService::class);

        $result = $getUserJourneyService->get(1);

        $this->assertEquals( collect([
            "id" => 1,
            "uid" => 1,
            "link" => "random-link",
            "linkType" => "product",
            "timestamp" => "2022,03-12 18:16:05"
        ]), $result);
    }

    /**
     * @dataProvider validationData
     */
    public function test_validator_works(array $data){
        ["id" => $id, "error" => $error] = $data;

        $userRepository = $this->mock(UserRepository::class);
        $userRepository->shouldReceive("userExistsById")
            ->with(2)
            ->andReturn(false);
        $userRepository->shouldReceive("userExistsById")
            ->with(-1)
            ->andReturn(true);

        /** @var GetUserJourneyService $getUserJourneyService */
        $getUserJourneyService = $this->app->get(GetUserJourneyService::class);

        try {
            $getUserJourneyService->get($id);
        } catch (\Exception $e){
            $this->assertEquals($error, $e->getMessage());
        }
    }

    public function validationData(): array
    {
        return [
            "test #1 - id is negative" => [
                [
                    "id" => -1,
                    "error" => "positive"
                ]
            ],
            "test #2 - user doesn't exist by id" => [
                [
                    "id" => 2,
                    "error" => "user does not exist"
                ]
            ]
        ];
    }
}
