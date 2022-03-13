<?php

namespace Services;

use App\Enums\LinksTypeEnums;
use App\Repositories\PostbackRepository;
use App\Repositories\User\UsersRepository;
use App\Services\PostbackService;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PostbackServiceTest extends TestCase
{
    public function test_it_works(){

        $postbackRepository = $this->mock(PostbackRepository::class);
        $postbackRepository->shouldReceive("createRollup")
            ->with(
                "freshful",
                LinksTypeEnums::HOMEPAGE,
                Carbon::createFromTimestamp("1646818364")->toDateTimeString(),
                1
            )->andReturn(true);

        /** @var PostbackService $postbackService */
        $postbackService = $this->app->get(PostbackService::class);

        $result = $postbackService->postback([
            "link" => "freshful",
            "linkType" => LinksTypeEnums::HOMEPAGE,
            "timestamp" => "1646818364",
            "uid" => 1
        ]);
    }

    /**
     * @dataProvider validationData
     */
    public function test_validator_works(array $data){
        ["input" => $input, "errors" => $errors] = $data;

        $usersRepository = $this->mock(UsersRepository::class);
        $usersRepository->shouldReceive("userExistsById")->with(1)->andReturn(false);
        $usersRepository->shouldReceive("userExistsById")->andReturn(true);

        /** @var PostbackService $postbackService */
        $postbackService = $this->app->get(PostbackService::class);

        try {
            $postbackService->postback($input);
        } catch (ValidationException $e){
            $this->assertEquals($errors, $e->validator->errors()->messages());
        }
    }

    public function validationData(): array
    {
        return [
          "test #1 - all required" => [
              [
                  "input" => [],
                  "errors" => [
                      "link" => ["required"],
                      "linkType" => ["required"],
                      "timestamp" => ["required"],
                      "uid" => ["required"]
                  ]
              ]
          ],
          "test #2 - link not string, linkType not in enum, timestamp not numeric, uid negative" => [
              [
                  "input" => [
                      "link" => 123,
                      "linkType" => "test",
                      "timestamp" => "test",
                      "uid" => -1
                  ],
                  "errors" => [
                      "link" => ["string"],
                      "linkType" => ["invalid"],
                      "timestamp" => ["numeric"],
                      "uid" => ["positive"]
                  ]
              ],
           "test #3 - user does not exist by id" => [
               [
                   "input" => [
                       "link" => "link",
                       "linkType" => LinksTypeEnums::cases()[0],
                       "timestamp" => "123",
                       "uid" => 1
                   ],
                   "errors" => [
                       "uid" => ["user does not exist"]
                   ]
               ]
           ]
          ]
        ];
    }
}
